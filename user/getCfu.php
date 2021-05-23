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

else{

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

    
?>
