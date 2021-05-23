<?php

    session_start();
    // includo il file per configurare la connessione al db postgres 
    require_once "../config/config.php";
    
    $id_utente = $_SESSION["id"];


if (isset($_GET["solo_num_cfu"])){

  $query = "SELECT sum(cfu) as numero_cfu
  FROM esame
  where id_utente= $1 and sostenuto=true";


  if($stmt = pg_prepare($link,"ps", $query)){
      
    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($id_utente))){
      
        $line = pg_fetch_assoc($result);
        $num_cfu=  $line['numero_cfu'];
        if ($num_cfu == ""){
          echo '0';
        }

        else{
          echo $num_cfu;
        }
        
      }
  

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }



}

else if(isset($_GET["cfu"])){

    // Prepare a select statement
    //    query the database in order to calculate aritmetic and ponderated mean.
    $query = "SELECT nome_esame, cfu
    FROM esame
    where id_utente= $1 and sostenuto=true
    ORDER BY esame.created_at ";
    //order by data_sostenuto

    if($stmt = pg_prepare($link,"ps", $query)){
        
      // Attempt to execute the prepared statement
      if($result=pg_execute($link,"ps",array($id_utente))){
        
        $table = array();
        $table['cols'] = array(
              array ('label'=> 'nome', 'type'=> 'string'),
              array('label'=> 'cfu', 'type'=> 'number')
        );
    
        $rows = array();
        while ( $line = pg_fetch_assoc($result)){
          
          $temp = array();
          $temp[] = array('v'=> $line['nome_esame']);
          $temp[] = array('v'=> (int) $line['cfu']);
    
          $rows[]= array ('c'=> $temp);
        }
    
        $table['rows'] = $rows;
        $json = json_encode($table);
        echo $json;

      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }
  }
}
else if (isset($_GET["solo_mediaP"])){
      
  $query = "SELECT voto,cfu
  FROM esame
  where id_utente= $1 and sostenuto=true";
  

  if($stmt = pg_prepare($link,"ps", $query)){
      
    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($id_utente))){
      
    
      $num_cfu=0;
      $tot_p=0;
      while ( $line = pg_fetch_assoc($result)){
        $tot_p = $tot_p + ((int) $line['voto'])*((int) $line['cfu']);
        $num_cfu = $num_cfu + (int) $line['cfu'];
      }
      if ($num_cfu == 0){
        $media_p = 0;
        echo $media_p;
      }
      else{
        $media_p = (float)$tot_p/$num_cfu;
        echo number_format($media_p,2);
      }
      
     
    }
  

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

else if(isset($_GET["pesata"])){
  // Prepare a select statement
  //    query the database in order to calculate aritmetic and ponderated mean.
  $query = "SELECT nome_esame, voto, cfu
  FROM esame
  where id_utente= $1 and sostenuto=true
  ORDER BY esame.created_at ";
  //order by data_sostenuto

  if($stmt = pg_prepare($link,"ps", $query)){
      
    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($id_utente))){
      
      $table = array();
      $table['cols'] = array(
            array ('label'=> 'nome', 'type'=> 'string'),
            array('label'=> 'media_p', 'type'=> 'number')
      );
  
      $rows = array();
      $tot_p = 0;
      $num_cfu= 0;
      $media_p = 0;
      while ( $line = pg_fetch_assoc($result)){
        $tot_p = $tot_p + ((int) $line['voto'])*((int) $line['cfu']);
        $num_cfu = $num_cfu + (int) $line['cfu'];
        $media_p = $tot_p/$num_cfu;
        $temp = array();
        $temp[] = array('v'=> $line['nome_esame']);
        $temp[] = array('v'=> $media_p);
  
        $rows[]= array ('c'=> $temp);
      }
  
      $table['rows'] = $rows;
      $json = json_encode($table);
      echo $json;

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}
}

else if (isset($_GET["solo_mediaA"])){

  $query = "SELECT avg(voto) as media
  FROM esame
  where id_utente= $1 and sostenuto=true";


  if($stmt = pg_prepare($link,"ps", $query)){
      
    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($id_utente))){
      
        $line = pg_fetch_assoc($result);
        $media=  (float)$line['media'];
        if ($media == 0){
          echo $media;
        }
        else{
          echo number_format($media,2);
        }
       
        
      }
  

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }



}
else if(isset($_GET["media"])){

// Prepare a select statement
//    query the database in order to calculate aritmetic and ponderated mean.
$query = "SELECT nome_esame, voto, cfu
FROM esame
where id_utente= $1 and sostenuto=true
ORDER BY data_sostenuto";


if($stmt = pg_prepare($link,"ps", $query)){
    
  // Attempt to execute the prepared statement
  if($result=pg_execute($link,"ps",array($id_utente))){
    
    $table = array();
    $table['cols'] = array(
          array ('label'=> 'nome', 'type'=> 'string'),
          array('label'=> 'media_a', 'type'=> 'number')
    );

    $rows = array();
    $tot_a = 0;
    $num_esami = 0;
    $media_a = 0;
    while ( $line = pg_fetch_assoc($result)){
      $tot_a = $tot_a+ (int) $line['voto'];
      $num_esami = $num_esami +1;
      $media_a = $tot_a/$num_esami;
      $temp = array();
      $temp[] = array('v'=> $line['nome_esame']);
      $temp[] = array('v'=> $media_a);

      $rows[]= array ('c'=> $temp);
    }

    $table['rows'] = $rows;
    $json = json_encode($table);
    echo $json;

  } else{
      echo "Oops! Something went wrong. Please try again later.";
  }
}
}

else if (isset($_GET["solo_num_esami"])){

  $query = "SELECT count(*) as numero_esami
  FROM esame
  where id_utente= $1 and sostenuto=true";


  if($stmt = pg_prepare($link,"ps", $query)){
      
    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($id_utente))){
      
        $line = pg_fetch_assoc($result);
        $num_esami=  (int)$line['numero_esami'];
        echo $num_esami;
        
      }
  

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }



}

else if(isset($_GET["voti_esami"])){

// Prepare a select statement
//    query the database in order to calculate aritmetic and ponderated mean.
$query = "SELECT nome_esame, voto
FROM esame
where id_utente= $1 and sostenuto=true
ORDER BY data_sostenuto";
//order by data_sostenuto

if($stmt = pg_prepare($link,"ps", $query)){
    
  // Attempt to execute the prepared statement
  if($result=pg_execute($link,"ps",array($id_utente))){
    
    $table = array();
    $table['cols'] = array(
          array ('label'=> 'nome', 'type'=> 'string'),
          array('label'=> 'voto', 'type'=> 'number')
    );

    $rows = array();
    while ( $line = pg_fetch_assoc($result)){
      
      $temp = array();
      $temp[] = array('v'=> $line['nome_esame']);
      $temp[] = array('v'=> (int) $line['voto']);

      $rows[]= array ('c'=> $temp);
    }

    $table['rows'] = $rows;
    $json = json_encode($table);
    echo $json;

  } else{
      echo "Oops! Something went wrong. Please try again later.";
  }
}

}

    
?>
