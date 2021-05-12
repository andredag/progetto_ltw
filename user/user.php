<?php
// Initialize the session
session_start();
// Include config file
require_once "../config/config.php";

 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login_registrazione/index.html");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/d30df16bb9.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../js/toogle_menu.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>User page</title>

    <!-- load JQuery-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
    <script src="./user.js"></script>
    
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="./chart.js"></script>
</head>
<body>

<section class="home" id="home">
    <!--navbar -->
    <nav id="nav_bar">
          <div class="logo"> 
             <img src="../images/logo.png" >
             <h1><?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
          </div>           
          <div class="nav-links" id="nav-links">
              <i class="far fa-times-circle" id="exit-icon" onclick="hideMenu()"></i> <!--exit-icon for smaller screens-->
              <ul>
                  <li><a href="#stats">Statistiche</a></li>
                  <li><a href="#esami">Esami</a></li>
              </ul>
          </div>
         <div class="bottoni">
              <a href="../logout/logout.php"><button class="cool-btn">Logout</button></a>
          </div>
          <i class="fas fa-bars" id="bar-icon" onclick="showMenu()"></i> <!--menu-icon for smaller screens-->
    </nav>

    <!-- stats -->
    <div class="stats" id= "stats">
       
        <div class="container">
            <div class="row">
                <div class="col col-title"><h3>Media Aritmetica</h3></div>
                <div class="col col-value"><h3>23</h3></div>
                <div class="col col-chart"><div id="chart-box"></div></div>
            </div>
            <div class="row">
                <div class="col col-title"><h3>Media Pesata</h3></div>
                <div class="col col-value"><h3>23</h3></div>
                <div class="col col-chart"><div id="chart-box"></div></div>
            </div>
            <div class="row">
                <div class="col col-title"><h3>Cfu</h3></div>
                <div class="col col-value">23/180</div>
                <div class="col col-chart"><div id="cfu-box"></div></div>
            </div>
            <div class="row">
                <div class="col col-title"><h3>Esami</h3></div>
                <div class="col col-value">2/21</div>
                <div class="col col-chart"><div id="num-box"></div></div>
            </div>
        </div>

        <!--
        <div class="riga_hint">

                <div class="hint">
                    <div id="chart-box"></div>
                </div>

                <div class="hint">
                    <div id="num-box"></div>
                </div>

                <div class="hint">
                    <div id="cfu-box"></div>
                </div>
        </div>-->
    
    </div>


</section>

<section class="esami" id="esami">
            <!-- tabella esami -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col"><button id="btn-esame">Aggiungi Esame</button></th>
                        <div class="info-esame" id="info-esame">
                            <div class="info-header">
                                <h1>Info esame</h1>
                                <button id="btn-close">&times;</button>
                            </div>
                            <div class="alert alert-warning" role="alert" id="alert" >
                            </div>
                            <div class="info-body">
                                <!--<form action="./invia_dati_esame.php" id="get-info" method="POST">-->
                                    <input type="text" placeholder="Nome" target="nome" required>
                                    
                                    <input type="text" placeholder="Cfu" target="cfu" required>
                                   
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" target="sostenuto" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Sostenuto
                                        </label>
                                    </div>
                                    
                                    <input type="text" placeholder="Voto" target="voto" id="voto_info" hidden=true>
                                    
                                    <button id="ok-btn" type="submit" value="ok" >Ok</button>
                                <!--</form>-->
                                <script>
                                    $("#flexCheckDefault").change(function(){
                                        
                                        var value = $(this).prop("checked");
                                        if(value==true) $("#voto_info").attr("hidden",false);
                                        else $("#voto_info").attr("hidden",true);
                                    })
                                </script>
                            </div>
                        </div>
                        <th scope="col">Esame</th>
                        <th scope="col">Sostenuto</th>
                        <th scope="col">Voto</th>
                        <th scope="col">Cfu</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                
                <tbody id="table_body">
                    <?php
                        //  query the database to get exams info.
                        $utente = $_SESSION["id"];
                        $query = "SELECT esame.nome_esame, esame.voto, esame.cfu , esame.sostenuto
                        FROM esame
                        where esame.id_utente = $1 
                        ORDER BY esame.created_at DESC";
                        //order by data_sostenuto
                        if($stmt = pg_prepare($link,"ps", $query)){
        
                            // Attempt to execute the prepared statement
                            if($result=pg_execute($link,"ps",array($utente))){
                                //to do 
                                while ( $line = pg_fetch_assoc($result)){
                                    $nome_esame= $line["nome_esame"];
                                    $voto=$line["voto"];
                                    $cfu=$line["cfu"];
                                    $sostenuto=$line["sostenuto"];
                                    if($sostenuto=="t"){
                                    echo "
                                    <tr class='riga_tabella'>
                                        <td></td>
                                        <td class='nome_esame'>$nome_esame </td>
                                        <td>
                                            <i class='fas fa-check-circle'> </i>
                                        </td>
                                        <td >$voto</td>
                                        <td>$cfu</td>
                                        <td><i class='fas fa-edit'></i></td>
                                        <td><i class='fas fa-trash-alt'></i></td>
                                    </tr>
                                    "; 
                                    }
                                    else{
                                        echo "
                                    <tr class='riga_tabella'>
                                        <td></td>
                                        <td class='nome_esame'>$nome_esame</td>
                                        <td>
                                            <i class='far fa-times-circle'></i>
                                        </td>
                                        <td >$voto</td>
                                        <td>$cfu</td>
                                        <td ><i class='fas fa-edit'></i></td>
                                        <td><i class='fas fa-trash-alt'></i></td>
                                    </tr>
                                    ";
                                    }
                                }
                            } else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }   
                    ?>
                </tbody>
            </table>
    </section>

        
</body>
</html>