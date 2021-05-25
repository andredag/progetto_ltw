<?php
// inizializza la sessione 
session_start();
 
// controlla se l'user è già loggato, in tal caso rimandalo nella sua pagina utente
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../user/user.php");
    exit;
}

if(isset($_SESSION["remember"])){
    header("location: ../user/user.php");
    exit;
}
 
// includo il file per configurare la connessione al db postgres 
require_once "../config/config.php";
 
// definisci variabili e inizializzazione
$username = $password = "";

 
// Se il metodo è post processa i dati
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        header("location: ./index.html");
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        header("location: ./index.html");
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = $1";
        if($stmt = pg_prepare($link,"ps", $sql)){
            // Set parameters
            $param_username = $username;
            // prima fa bind dei parametri alla query e poi prova ad eseguirla            
            if($result=pg_execute($link,"ps",array($param_username))){
                
                // Check if username exists, if yes then verify password  
                if(pg_numrows($result) == 1){                   
                    $password_db=pg_result($result,0,'password');
                    $id=pg_result($result,0,'id');
                    if($password == $password_db){
                        // Password is correct, so start a new session
                        session_start();
                            
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username; 
                        
                        //se la checkbox di remember è settata a true
                        //setta i cookie per un'ora
                        if(!empty($_POST["remember"]) ){
                            setcookie ("username", $username ,time()+ 3600);
                            setcookie ("password", $password ,time()+ 3600);
                        }
                        else{
                            setcookie("username","");
	                        setcookie("password","");
                        }
                            
                        // Redirect user to welcome page
                        header("location: ../user/user.php");
                    } else{
                        // Password is not valid, display a generic error message
                        header("location: ./index.html");
                    }
                    
                } else{
                    // Username doesn't exist, display a generic error message
                    
                    header("location: ../login_registrazione/index.html");
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    //chiudo la connessione con il server 
    pg_close($link);
}
?>
