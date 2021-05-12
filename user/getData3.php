<?php

    session_start();
    // includo il file per configurare la connessione al db postgres 
    require_once "../config/config.php";
    
    $id_utente = $_SESSION["id"];

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
              array('label'=> 'media_a', 'type'=> 'number'),
              array('label'=> 'media_p', 'type'=> 'number')
        );
    
        $rows = array();
        $tot_a = 0;
        $tot_p = 0;
        $num_esami = 0;
        $num_cfu= 0;
        $media_a = 0;
        $media_p = 0;
        while ( $line = pg_fetch_assoc($result)){
          $tot_a = $tot_a+ (int) $line['voto'];
          $tot_p = $tot_p + ((int) $line['voto'])*((int) $line['cfu']);
          $num_esami = $num_esami +1;
          $num_cfu = $num_cfu + (int) $line['cfu'];
          $media_a = $tot_a/$num_esami;
          $media_p = $tot_p/$num_cfu;
          $temp = array();
          $temp[] = array('v'=> $line['nome_esame']);
          $temp[] = array('v'=> $media_a);
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
  

    
?>
