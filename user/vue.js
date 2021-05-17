var tab_esame = new Vue({
    el: '#table_body',
    data: {
      esami: "ciao"
      
    },
    methods: {
      //in questa funzione apro il modal e carico il necessario per visualizzare i dettagli dell'esame
      apri_modal_esame: function(event){
        
        $("#modal-esame").modal('show');
        modal_esame.nome_esame=event.target.parentElement.parentElement.children[1].innerText;
        axios.get('getDatiEsame.php',{params: {nome: modal_esame.nome_esame, richiesta_dati:"nome_esame"}})
        .then(function (response) {
           modal_esame.esame = response.data;
           modal_esame.getArg();
           modal_esame.getLinks();
           modal_esame.getNote();
        })
        .catch(function (error) {
           console.log(error);
        });

      },
      //funzione che carica tutti gli esami dell'utente nell'array esami poi l'html di user.php farà il rendering
      allRecords: function(){
  
        axios.get('getDatiEsame.php', {params:{richiesta_dati:"lista_esami"}})
        .then(function (response) {
           tab_esame.esami = response.data;
        })
        .catch(function (error) {
           console.log(error);
        });
      },

      //funzione da agganciare al bottone rimuovi esame
      rimuovi_esame: function(event){
        var conferma= confirm("Sei sicuro di voler rimuovere l'esame con tutti i suoi contenuti?");
        if(!conferma) return false;
        //rimuovo il codice html dalla pagina
        event.target.parentElement.parentElement.remove();
        //salvo il nome dell'esame da eliminare
        var nome_esame=event.target.parentElement.parentElement.children[1].innerText;
        //lancio una chiamata asincrona al db per eliminare l'esame
        $.ajax({
            type: "POST",
            url: "./invia_dati_esame.php",
            data: "nome_esame="+nome_esame+"&remove=true",
            dataType: "html" ,
            success: function(msg){
                //alert("esame rimosso");
            },
            error: function(msg){
                alert("errore");
            }
            
        });
        //aggiorna i grafici e i numeri vicino.
        $.getScript("./chart.js");
        this.scrivi_numeri();
    },
    //funzione per aggiornare media a/p e cfu
    scrivi_numeri: function (){  
      $.ajax({
          url: "./getMediaA.php",
          data: "solo_media",
          success: function(msg){
              $("#title-media-a").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
      $.ajax({
          url: "./getMediaP.php",
          data: "solo_media",
          success: function(msg){
              $("#title-media-p").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
  
      $.ajax({
          url: "./getVoti.php",
          data: "solo_num_esami",
          success: function(msg){
              $("#title-esami").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
      
      $.ajax({
          url: "./getCfu.php",
          data: "solo_num_cfu",
          success: function(msg){
              $("#title-cfu").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
      }
    
    },
    //alla creazione di questo oggetto lancio la funzione allRecords per recuperare dal db gli esami. 
    created: function(){
        this.allRecords()
    }
})

var modal_esame = new Vue({
  el: '#body-modal-esame',
  data: {
    nome_esame: " " ,
    esame: " ",
    argomenti: " ",
    links: " ",
    note: " "

    
  },
  methods: {
      getArg:function(){
        axios.get('getDatiEsame.php',{params: {nome: modal_esame.nome_esame, richiesta_dati: "argomenti"}})
        .then(function (response) {
           modal_esame.argomenti = response.data;
        })
        .catch(function (error) {
           console.log(error);
        });
      },
      getLinks:function(){
        axios.get('getDatiEsame.php',{params: {nome: modal_esame.nome_esame, richiesta_dati:"link"}})
        .then(function (response) {
           modal_esame.links = response.data;
        })
        .catch(function (error) {
           console.log(error);
        });
      },
      getNote:function(){
        axios.get('getDatiEsame.php',{params: {nome: modal_esame.nome_esame, richiesta_dati: "note"}})
        .then(function (response) {
           modal_esame.note = response.data;
        })
        .catch(function (error) {
           console.log(error);
        });
      }
  }
})
