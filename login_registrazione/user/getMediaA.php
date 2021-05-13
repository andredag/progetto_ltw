<?php

    session_start();
    // includo il file per configurare la connessione al db postgres 
    require_once "../config/config.php";
    
    $id_utente = $_SESSION["id"];


    if (isset($_GET["solo_media"])){

      $query = "SELECT avg(voto) as media
      FROM esame
      where id_utente= $1 and sostenuto=true";

  
      if($stmt = pg_prepare($link,"ps", $query)){
          
        // Attempt to execute the prepared statement
        if($result=pg_execute($link,"ps",array($id_utente))){
          
            $line = pg_fetch_assoc($result);
            $media=  (float)$line['media'];
            echo number_format($media,2);
            
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

    
?>
