<?php

session_start();
// includo il file per configurare la connessione al db postgres 
require_once "../config/config.php";





$utente = $_SESSION["id"];
$query = "SELECT esame.nome_esame, esame.voto, esame.cfu , esame.sostenuto
FROM esame
where esame.id_utente = $1 and esame.nome_esame = $2";

$response = "";

if($stmt = pg_prepare($link,"ps", $query)){

    // Attempt to execute the prepared statement
    if($result=pg_execute($link,"ps",array($utente,$_GET["nome"]))){
                
        while ( $line = pg_fetch_assoc($result)){
            $nome_esame= $line["nome_esame"];
            $voto=$line["voto"];
            $cfu=$line["cfu"];
            $sostenuto=$line["sostenuto"];
            $response=$line;
        }

        echo json_encode($response);
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

pg_close($link);    
?>
