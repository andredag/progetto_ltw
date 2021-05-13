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
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
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
                        $login_err = "Invalid username or password.";
                    }
                    
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
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
