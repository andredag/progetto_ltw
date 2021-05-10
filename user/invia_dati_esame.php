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
        $id = $_SESSION["id"];
        if($sostenuto=="false") $voto=0;
        $query = "INSERT INTO esame(nome_esame,voto,cfu,id_utente,sostenuto) VALUES($1,$2,$3,$4,$5)";
        if($stmt = pg_prepare($link,"ps", $query)){
        
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($nome,$voto,$cfu,$id,$sostenuto))){
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
}
pg_close($link);
?>