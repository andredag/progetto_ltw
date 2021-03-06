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
    <title>User page</title>

    <!-- Impoto fogli di stile -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Importo script vari -->
    <script src="https://kit.fontawesome.com/d30df16bb9.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../js/toogle_menu.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <!-- load JQuery-->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    
    <!--Load the Google Chart's API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="./chart.js"></script>

    <!-- Importo framework Vue.js e libreria Axios -->
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
                  <li><a href="#">Statistiche</a></li>
                  <li><a href="#esami">Esami</a></li>
              </ul>
          </div>
         <div class="bottoni">
              <a href="../logout/logout.php"><button class="cool-btn">Logout</button></a>
          </div>
          <i class="fas fa-bars" id="bar-icon" onclick="showMenu()"></i> <!--menu-icon for smaller screens-->
    </nav>
    <br>
    <br>
    <!-- stats -->
    <div class="stats" id= "stats">
       
        <div class="container-fluid">
            <div class="row">
                <div class="col "><h3>Media Aritmetica</h3></div>
                <div class="col "><h3 id="title-media-a"></h3></div>
                <div class="col col-chart"><div class = "chart-box" id="media-aritmetica-box"></div></div>
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
        <button type="button" class="close close_modal exit_modal_esame" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="body-modal-esame">
        
        <!-- sezione info esame -->
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

        <!-- sezione programma esame -->
        <div class="modal-box" id="programma-box">

            <h1 class="box-title">PROGRAMMA</h1>
            <i class="fas fa-plus-square" id="btn-arg" ></i>

            <div class="form_prog" id="form_prog">
                
                <input type="text" name="nome_argomento" placeholder="Nome Argomento" target="nome_arg">
                <select name="pallino" target="pallino" >
                    <option></option>
                    <option>Ottimo</option>
                    <option>Discreto</option>
                    <option>Da Rivedere</option>
                </select>
                <h1 id="errore_argomento" hidden="true">{{errore}}</h1>
                <i class="far fa-check-circle" v-on:click="aggiungi_arg"></i>
                <i class="fas fa-times-circle" id="exit-arg"></i>
            </div>

            <ol id="lista_arg">
                
                <li v-for="arg in argomenti">
                
                    <div class="col col-4 col-edit-arg">

                        <h3 v-if="arg.pallino=='Ottimo'"> <i class="fas fa-circle" id="circle-ottimo"></i></h3>
                        <h3 v-else-if="arg.pallino=='Discreto'"><i class="fas fa-circle" id="circle-discreto"></i></h3>
                        <h3 v-if="arg.pallino=='Da Rivedere'"> <i class="fas fa-circle" id="circle-daRivedere"></i></h3>
                        
                        <i class='fas fa-edit open_edit_arg' v-on:click="open_edit_arg"></i>

                        <i class='fas fa-times-circle chiudi_edit_arg' v-on:click="chiudi_edit_arg" style="display:none"></i>

                        <i class='fas fa-check-circle edit_arg' v-on:click="edit_arg" style="display:none"></i>
                        
                        <select class="new_pallino" name="new_pallino" style="display:none">
                            <option></option>
                            <option>Ottimo</option>
                            <option>Discreto</option>
                            <option>Da Rivedere</option>
                        </select>

                    </div>

                    <div class="col col-nome-arg">
                        <h3 class= "nome_argomento">{{arg.nome_argomento}}</h3>
                    </div>

                    <div class="col col-1 col-rimuovi-arg">
                        <i v-on:click="rimuovi_arg" class='fas fa-trash-alt'></i>
                    </div>

                   
                   
                </li>
            </ol>
        </div>
        

        <hr>

        <!-- sezione Links esame -->
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
                    
                        <div class="col col-link">
                            <a v-bind:href='link.url' target="_blank" rel="noopener noreferrer">
                                <h2>{{link.descrizione_link}}</h2>
                            </a>
                        </div>
                       <div class= "col col-1">
                            <i class="fas fa-trash-alt" v-on:click="rimuovi_link" ></i>
                       </div>
                       
                    
                </li>
            </ul>
        </div>
        
        <hr>

        <!-- sezione Note esame -->
        <div class="modal-box" id="note-box">
            <h1 class="box-title">NOTE</h1>
            <i class="fas fa-plus-square" id="btn-nota" ></i>
            <div class="form_prog" id="form_note">
                <!--<input type="text" name="descrizione" placeholder="Descrizione" target="nota">-->
                <div>
                    <input type="text" name="descrizione" placeholder="descrizione" target="descrizione">
                    <textarea name="contenuto" placeholder="Contenuto" target="nota" cols="40" rows="5"></textarea>
                </div>
               
                <i class="far fa-check-circle" v-on:click="aggiungi_nota"></i>
                <i class="fas fa-times-circle" id="exit-nota"></i>
            </div>

            <ul id="lista_note">
                <li v-for="nota in note">
                    
                        <div class="col col-2">
                            <i class="fas fa-arrow-down" v-on:click="mostra_contenuto"></i>
                            <i class="fas fa-arrow-up nascondi_contenuto" style="display:none" v-on:click="nascondi_contenuto"></i>
                        </div>

                        <div class="col col-nota">
                            <h2 class = "descrizione-nota">{{nota.descrizione}}</h2>
                            <textarea name="contenuto-nota" class="contenuto-nota" cols="30" rows="10">{{nota.contenuto}}</textarea>
                        </div>

                        <div class="col col-1">
                            <i v-on:click="rimuovi_nota" class='fas fa-trash-alt'></i>
                        </div> 
                    
                </li>
            </ul>
        </div>
       
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close_modal close exit_modal_esame" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="./user.js"></script> 
<script src="./vue.js"></script>

      
</body>
</html>