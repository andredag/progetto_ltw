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
             <img src="../images/Exams-rafiki.png" >
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
       
        <div class="container-fluid">
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
                        <!-- Modal -->
                        <th scope="col" id="aggiungi_box">
                            <i id="aggiungi-btn" data-toggle="modal" data-target="#modal-aggiungi" class="fas fa-plus-square"></i>
                            <br>
                            <h4>Aggiungi esame</h4>
                        </th>
                        <th scope="col">Esame</th>
                        <th scope="col">Sostenuto</th>
                        <th scope="col">Voto</th>
                        <th scope="col">Cfu</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Remove</th>
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
                        <td>{{esame.voto}}</td>
                        <td>{{esame.cfu}}</td>
                        <td><i v-on:click="apri_modal_esame" class='fas fa-edit'></i></td>
                        <td><i v-on:click="rimuovi_esame" class='fas fa-trash-alt'></i></td>
                    </tr>                   
                </tbody>
                
            </table>
            
</section>

<!-- Modal Aggiungi Esame -->
<div class="modal fade" id="modal-aggiungi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Info esame</h5>
                <button type="button" id="btn-close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                                        
            <div class="alert alert-warning" role="alert" id="alert" >
            </div>
                                        
            <div class="modal-body info-body">
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
                <button id="ok-btn" type="submit" value="ok"  data-dismiss="modal">Ok</button>
                                            
                                            
            </div>
        </div>
                                    
    </div>
</div>
<!-- Modal esame HTML -->
<div class="modal fade" id="modal-esame" tabindex="-1" role="dialog" aria-labelledby="modal-esame-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modal-esame-title">INFO ESAME</h5>
        <button type="button" class="close close_modal" id="exit_modal_esame" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="body-modal-esame">

        <div class= "modal-box" id="info-box">
            <h1 class="box-title">DATI GENERALI</h1>
            <h2>Nome: {{esame.nome_esame}}</h2>
            <h2 v-if="esame.sostenuto=='t'">Sostenuto: sostenuto </h2>
            <h2 v-else>Sostenuto: Ancora da sostenere </h2>
            <h2>CFU: {{esame.cfu}}</h2>
            <h2>Voto: {{esame.voto}}</h2>
            <h2>Data Sostenuto: {{esame.data_sostenuto}}</h2>
        </div>
        
        <hr>


        <div class="modal-box" id="programma-box">

            <h1 class="box-title">PROGRAMMA</h1>
            <i class="fas fa-plus-square" id="btn-arg" ></i>

            <button id="ordina_arg">Ordina argomenti</button>

            <div class="form_prog" id="form_prog">
                
                <input type="text" name="nome_argomento" placeholder="Nome Argomento" target="nome_arg">
                <select name="pallino" target="pallino" >
                    <option></option>
                    <option>Ottimo</option>
                    <option>Meh </option>
                    <option>Da Rivedere</option>
                </select>
                <h1 id="errore_argomento" hidden="true">{{errore}}</h1>
                <i class="far fa-check-circle" v-on:click="aggiungi_arg"></i>
                <i class="fas fa-times-circle" id="exit-arg"></i>
            </div>

            <ol id="lista_arg">
                
                <li v-for="arg in argomenti">
                <div class="riga_arg">

                    <h3 v-if="arg.pallino=='Ottimo'"> <i class="fas fa-circle" id="circle-ottimo"></i></h3>
                    <h3 v-else-if="arg.pallino=='Meh'"><i class="fas fa-circle" id="circle-meh"></i></h3>
                    <h3 v-if="arg.pallino=='Da Rivedere'"> <i class="fas fa-circle" id="circle-daRivedere"></i></h3>
                    
                    <h3>{{arg.nome_argomento}}</h3>
                   
                    <i v-on:click="rimuovi_arg" class='fas fa-trash-alt'></i>
                </div>
                </li>
            </ol>
        </div>
        

        <hr>

        <div class="modal-box" id="link-box">
            <h1 class="box-title">LINKS</h1>
            <i class="fas fa-plus-square" id="btn-link" ></i>

            <div class="form_prog" id="form_link">
                
                <input type="text" name="descrizione_link" placeholder="Descrizione Link" target="descrizione_link">
                <input type="text" name="url" placeholder="URL" target="url">
                <i class="far fa-check-circle" v-on:click="aggiungi_link"></i>
                <i class="fas fa-times-circle" id="exit-link"></i>
            </div>
            <ul id="lista_link">
                <li v-for="link in links">
                    <div class="riga_link">
                        <a v-bind:href='link.url' target="_blank" rel="noopener noreferrer">
                            <h2>{{link.descrizione_link}}</h2>
                        </a>
                        <i v-on:click="rimuovi_link" class='fas fa-trash-alt'></i>
                    </div>
                </li>
            </ul>
        </div>
        
        <hr>

        <div class="modal-box" id="note-box">
            <h1 class="box-title">NOTE</h1>
            <i class="fas fa-plus-square" id="btn-nota" ></i>
            <div class="form_prog" id="form_note">
                <!--<input type="text" name="descrizione" placeholder="Descrizione" target="nota">-->
                <input type="text" name="descrizione" placeholder="descrizione" target="descrizione">
                <textarea name="contenuto" placeholder="Contenuto" target="nota" cols="40" rows="5"></textarea>
                <i class="far fa-check-circle" v-on:click="aggiungi_nota"></i>
                <i class="fas fa-times-circle" id="exit-nota"></i>
            </div>
            <ul id="lista_note">
                <li v-for="nota in note">
                    <div class="riga_nota">
                    <h2>{{nota.descrizione}}</h2>
                    <i class="fas fa-arrow-down" v-on:click="mostra_contenuto"></i>
                    <i class="fas fa-arrow-up n"  v-on:click="nascondi_contenuto"></i>
                    <h2 class="contenuto-nota">{{nota.contenuto}}</h2>
                    <i v-on:click="rimuovi_nota" class='fas fa-trash-alt'></i>
                    </div>
                </li>
            </ul>
        </div>
       
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close_modal close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="./user.js"></script> 
<script src="./vue.js"></script>

      
</body>
</html>