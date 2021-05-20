<?php
session_start();
// Include config file
require_once "../config/config.php";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //se la post è per aggiungere un esame
    if(isset($_POST["nome_esame"]) && isset($_POST["add"]) && $_POST["add"]==true){
        $nome = $_POST["nome_esame"];
        $voto = $_POST["voto"];
        $cfu = $_POST["cfu"];
        $sostenuto =$_POST["sostenuto"];
        $data_sostenuto = $_POST["data_sostenuto"];
        $id = $_SESSION["id"];
        if($sostenuto=="false") $voto=0;
        if($data_sostenuto=="") $data_sostenuto=NULL;
        $query = "INSERT INTO esame(nome_esame,voto,cfu,id_utente,sostenuto,data_sostenuto) VALUES($1,$2,$3,$4,$5,$6)";
        if($stmt = pg_prepare($link,"ps", $query)){
        
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($nome,$voto,$cfu,$id,$sostenuto,$data_sostenuto))){
                echo "ok";//header("location: ./user.php#esami");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    //se la post è per rimuovere un esame 
    else if(isset($_POST["nome_esame"]) && isset($_POST["remove"]) && $_POST["remove"]==true){
        
        $query = "DELETE FROM esame where id_utente= $1 and nome_esame = $2";
        if($stmt = pg_prepare($link,"pss", $query)){
            // Set parameters
            $id = $_SESSION["id"];
            $nome = $_POST["nome_esame"];
                            
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($id,$nome))){
                echo "ok"; 
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    else if(isset($_POST["add_arg"]) && ($_POST["add_arg"]==true)){
        $arg=$_POST["arg"];
        $pallino=$_POST["pallino"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="INSERT INTO argomento(nome_argomento,pallino,nome_esame,id_utente) VALUES($1,$2,$3,$4)";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($arg,$pallino,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }

    else if(isset($_POST["add_nota"]) && ($_POST["add_nota"]==true)){
        $nota=$_POST["nota"];
        $descrizione = $_POST["descrizione"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="INSERT INTO nota(descrizione,contenuto,nome_esame,id_utente) VALUES($1,$2,$3,$4)";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($descrizione,$nota,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }
    else if(isset($_POST["add_link"]) && ($_POST["add_link"]==true)){
        $descrizione_link=$_POST["descrizione_link"];
        $url = $_POST["url"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="INSERT INTO link(descrizione_link,url,nome_esame,id_utente) VALUES($1,$2,$3,$4)";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($descrizione_link,$url,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }
    else if(isset($_POST["remove_arg"]) && ($_POST["remove_arg"]==true)){
        $arg=$_POST["arg"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="DELETE FROM argomento WHERE nome_argomento=$1 AND nome_esame=$2 AND id_utente=$3";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($arg,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }
    else if(isset($_POST["remove_link"]) && ($_POST["remove_link"]==true)){
        $desc_link=$_POST["link"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="DELETE FROM link WHERE descrizione_link=$1 AND nome_esame=$2 AND id_utente=$3";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($desc_link,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }

    else if(isset($_POST["remove_nota"]) && ($_POST["remove_nota"]==true)){
        $descrizione=$_POST["descrizione"];
        $esame=$_POST["esame"];
        $id=$_SESSION["id"];
        $query="DELETE FROM nota WHERE descrizione=$1 AND nome_esame=$2 AND id_utente=$3";
        if($stmt = pg_prepare($link,"pss", $query)){
           
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($descrizione,$esame,$id))){
                $response="ok";
                echo json_encode($response);
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    }


    else echo("non eseguo nessuna query");
}

pg_close($link);
?>