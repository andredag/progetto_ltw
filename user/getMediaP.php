<?php

  session_start();
    // includo il file per configurare la connessione al db postgres 
  require_once "../config/config.php";
    
  $id_utente = $_SESSION["id"];

  if (isset($_GET["solo_media"])){
      
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

  else{
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

    
?>
