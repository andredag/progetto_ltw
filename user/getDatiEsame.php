<?php

session_start();
// includo il file per configurare la connessione al db postgres 
require_once "../config/config.php";


if (isset($_GET["richiesta_dati"]) ){

    if ($_GET["richiesta_dati"]=="note"){

        $utente = $_SESSION["id"];
        $query = "SELECT nota.descrizione , nota.contenuto
        FROM nota
        where nota.id_utente = $1 and nota.nome_esame = $2";

        $response = array();

        if($stmt = pg_prepare($link,"ps", $query)){

            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($utente,$_GET["nome"]))){
                        
                while ( $line = pg_fetch_assoc($result)){
                    
                    $response[]=$line;
                }

                echo json_encode($response);
            } 
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    else if ($_GET["richiesta_dati"]=="argomenti"){

        $utente = $_SESSION["id"];
        $query = "SELECT argomento.nome_argomento, argomento.pallino
        FROM argomento
        where argomento.id_utente = $1 and argomento.nome_esame = $2";

        $response = array();

        if($stmt = pg_prepare($link,"ps", $query)){

            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($utente,$_GET["nome"]))){
                        
                while ( $line = pg_fetch_assoc($result)){
                    
                    $response[]=$line;
                }

                echo json_encode($response);
            } 
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    else if($_GET["richiesta_dati"]=="link"){

        $utente = $_SESSION["id"];
        $query = "SELECT link.descrizione_link, link.url
        FROM link
        where link.id_utente = $1 and link.nome_esame = $2";

        $response = array();

        if($stmt = pg_prepare($link,"ps", $query)){

            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($utente,$_GET["nome"]))){
                        
                while ( $line = pg_fetch_assoc($result)){
                    
                    $response[]=$line;
                }

                echo json_encode($response);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    else if ($_GET["richiesta_dati"]=="nome_esame"){
        
        $utente = $_SESSION["id"];
        $query = "SELECT esame.nome_esame, esame.voto, esame.cfu , esame.sostenuto , 
        esame.data_sostenuto
        FROM esame
        where esame.id_utente = $1 and esame.nome_esame = $2";

        $response = "";

        if($stmt = pg_prepare($link,"ps", $query)){

            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($utente,$_GET["nome"]))){
                        
                while ( $line = pg_fetch_assoc($result)){
                    
                    $response=$line;
                }

                echo json_encode($response);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    else if ($_GET["richiesta_dati"]=="lista_esami"){

        $utente = $_SESSION["id"];
        $query = "SELECT esame.nome_esame, esame.voto, esame.cfu , esame.sostenuto
        FROM esame
        where esame.id_utente = $1 
        ORDER BY esame.created_at DESC";

        $response = array();

        if($stmt = pg_prepare($link,"ps", $query)){

            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($utente))){
                        
                while ( $line = pg_fetch_assoc($result)){
                    $nome_esame= $line["nome_esame"];
                    $voto=$line["voto"];
                    $cfu=$line["cfu"];
                    $sostenuto=$line["sostenuto"];
                    $response[]=$line;
                }

                echo json_encode($response);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    else{}
}

pg_close($link);    
?>
