<?php

    session_start();
    // includo il file per configurare la connessione al db postgres 
    require_once "../config/config.php";
    
    $utente = $_SESSION["username"];

    // Prepare a select statement
    //    query the database in order to calculate aritmetic and ponderated mean.
    $query = "SELECT esame.nome_esame, esame.voto
    FROM esame JOIN users on users.id =esame.id_utente
    where esame.sostenuto = true and users.username = $utente";
    //order by data_sostenuto

    $result = pg_query($query);
    
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
    
?>
