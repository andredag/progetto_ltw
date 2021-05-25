<?php
// includo il file per configurare la connessione al db postgres
require_once "../config/config.php";
 
// definisci variabili e inizializzazione
$username = $password = $confirm_password = "";
 
// Se il metodo è post processa i dati
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = $1";
        
        if($stmt = pg_prepare($link,"ps", $sql)){
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"ps",array($param_username))){
                if(pg_numrows($result) == 1){ 
                    header("location: ./index.html?register=true");
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        header("location: ./index.html?register=true");   
    } elseif(strlen(trim($_POST["password"])) < 3){
        header("location: ./index.html?register=true");
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        header("location: ./index.html?register=true");     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            header("location: ./index.html?register=true");
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES ($1, $2)";
         
        if($stmt = pg_prepare($link,"pss", $sql)){
            // Set parameters
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if($result=pg_execute($link,"pss",array($param_username,$param_password))){
                // Redirect to login page
                header("location: ./index.html");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    pg_close($link);
}
?>

