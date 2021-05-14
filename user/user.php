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
    <script src="../js/bootstrap.min.js"></script>
    <title>User page</title>

    <!-- load JQuery-->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="./user.js"></script> 
    
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="./chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>


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
                <div class="col "><h3>Media Aritmetica</h3></div>
                <div class="col "><h3 id="title-media-a"></h3></div>
                <div class="col"><div class = "chart-box" id="media-aritmetica-box"></div></div>
            </div>
            <div class="row">
                <div class="col "><h3>Media Pesata</h3></div>
                <div class="col "><h3 id="title-media-p"></h3></div>
                <div class="col "><div class = "chart-box" id="media-pesata-box"></div></div>
            </div>
            <div class="row">
                <div class="col "><h3>Cfu</h3></div>
                <div class="col "><h3 id="title-cfu"></h3></div>
                <div class="col"><div class = "chart-box" id="cfu-box"></div></div>
            </div>
            <div class="row">
                <div class="col "><h3>Esami</h3></div>
                <div class="col "><h3 id="title-esami"></h3></div>
                <div class="col"><div class = "chart-box" id="voti-box"></div></div>
            </div>
        </div>
    </div>


</section>

<section class="esami" id="esami">
            <!-- tabella esami -->
            <table class="table table-hover">
                <thead>
                    <tr>
                       
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
                                    <br>
                                    <input type="date" target="data_sostenuto" id="data_sostenuto" hidden=true>
                                    <button id="ok-btn" type="submit" value="ok" >Ok</button>
                                <!--</form>-->
                                <script>
                                    $("#flexCheckDefault").change(function(){
                                        
                                        var value = $(this).prop("checked");
                                        if(value==true){
                                             $("#voto_info").attr("hidden",false);
                                             $("#data_sostenuto").attr("hidden",false);
                                        }
                                        else {
                                            $("#voto_info").attr("hidden",true);
                                            $("#data_sostenuto").attr("hidden",true);
                                        }

                                    })
                                </script>
                            </div>
                        </div>
                        <th scope="col" id="aggiungi_box">
                            <i id="aggiungi-btn" class="fas fa-plus-square"></i>
                            <br>
                            <h4>Aggiungi esame</h4>
                        </th>
                        <th scope="col">Esame</th>
                        <th scope="col">Sostenuto</th>
                        <th scope="col">Voto</th>
                        <th scope="col">Cfu</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <!--ho associato a #table_body un oggetto vue definito in vue.js-->
                <tbody id="table_body">
                    <tr class='riga_tabella' v-for="esame in esami">
                        <td></td>
                        <td class='nome_esame'>{{esame.nome_esame}}</td>
                        <td>
                        <i v-if="esame.sostenuto=='t'" class='fas fa-check-circle'> </i>
                        <i v-else class='far fa-times-circle'></i>
                        </td>
                        <td >{{esame.voto}}</td>
                        <td>{{esame.cfu}}</td>
                        <td><i class='fas fa-edit'></i></td>
                        <td><i class='fas fa-trash-alt'></i></td>
                    </tr>
                    
                </tbody>
                <script src="./vue.js"></script>
            </table>
            
    </section>



<!-- Modal HTML -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
        <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body">
        .ndfgdndn
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>

      
</body>
</html>