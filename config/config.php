<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'easyuni');
 
/* Attempt to connect to MySQL database */
//$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$link = pg_connect("host=localhost port=5432 dbname=easy-uni user=postgres password=postgres");
 
// Check connection
if($link === false){
    //die("ERROR: Could not connect. " . mysqli_connect_error());
    die("ERROR: Could not connect. " . pg_last_error());
}
?>