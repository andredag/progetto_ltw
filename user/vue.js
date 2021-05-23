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
                //alert(msg);
            },
            error: function(msg){
                alert(msg);
            }
            
        });
        //aggiorna i grafici e i numeri vicino.
        $.getScript("./chart.js");
        this.scrivi_numeri();
    },
    //funzione per aggiornare media a/p e cfu
    scrivi_numeri: function (){  
      $.ajax({
          url: "./getStats.php",
          data: "solo_mediaA",
          success: function(msg){
              $("#title-media-a").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
      $.ajax({
          url: "./getStats.php",
          data: "solo_mediaP",
          success: function(msg){
              $("#title-media-p").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
  
      $.ajax({
          url: "./getStats.php",
          data: "solo_num_esami",
          success: function(msg){
              $("#title-esami").text(msg);
          },
          error: function(msg){
              alert("errore");
          },
      
      });
  
      
      $.ajax({
          url: "./getStats.php",
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
    note: " ",
    errore: " "

    
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
      },
      aggiungi_arg:function(){
        var arg=$("#form_prog input[target=nome_arg]").val();
        var pallino= $("#form_prog select[target=pallino]").val();

        if(arg=='' || pallino=='') {
          return; 
        }
        
        axios.post("invia_dati_esame.php",
          'add_arg=true'+
          '&arg='+arg+
          '&pallino='+pallino+
          '&esame='+this.nome_esame
        ).then(function(response){
          modal_esame.getArg();
        });

        // pulisci i campi dopo aver inserito un argomento
        $("#form_prog input[target=nome_arg]").val("");
        $("#form_prog select[target=pallino]").val("");

      },

      aggiungi_nota: function(){
        var nota=$("#form_note textarea").val();
        var descrizione = $("#form_note input[target = descrizione]").val();

        if (nota == "" || descrizione=="")   return;

        axios.post("invia_dati_esame.php",
        'add_nota=true'+
        '&nota='+nota+
        '&descrizione='+descrizione+
        '&esame='+this.nome_esame
        ).then(function(response){
        modal_esame.getNote();
        });

        // pulisce il campo dopo aver aggiunto una nota
        $("#form_note textarea").val("");
        $("#form_note input[target = descrizione]").val("");
      
      },

      aggiungi_link: function(){
        var descrizione_link = $("#form_link input[target=descrizione_link]").val();
        var url = $("#form_link input[target=url]").val();

        if (url == '' || descrizione_link=='')  return;

        axios.post("invia_dati_esame.php",
        'add_link=true'+
        '&descrizione_link='+descrizione_link+
        '&url='+url+
        '&esame='+this.nome_esame
        ).then(function(response){
        modal_esame.getLinks();
        });

        // pulisci i campi dopo aver aggiunto un link
        $("#form_link input[target=descrizione_link]").val("");
        $("#form_link input[target=url]").val("");

     
      },
      rimuovi_arg: function(event){
        var colonna = event.target.parentElement;
        var nome_arg = $(colonna).siblings(".col-nome-arg").children(".nome_argomento").text();
        //alert(nome_arg);

        axios.post("invia_dati_esame.php",
        'remove_arg=true'+
        '&arg='+nome_arg+
        '&esame='+this.nome_esame
        ).then(function(response){
          
          modal_esame.getArg();
        });        
      },
      rimuovi_link: function(event){
      
        var div =event.target.parentElement;
        var link = $(div).siblings(".col-link").children().children().text();
        //alert(link);
      
        axios.post("invia_dati_esame.php",
        'remove_link=true'+
        '&link='+link+
        '&esame='+this.nome_esame
        ).then(function(response){
          
          modal_esame.getLinks();
        });     
      },
      rimuovi_nota: function(event){

        var btn = event.target;
        var descrizione = $(btn).parent().siblings(".col-nota").children(".descrizione-nota").text();
        //alert(descrizione);
        
        axios.post("invia_dati_esame.php",
        'remove_nota=true'+
        '&descrizione='+descrizione+
        '&esame='+this.nome_esame
        ).then(function(response){
          
          modal_esame.getNote();
        });       
      },

      /*area contenuto nota*/ 
      mostra_contenuto: function(event){
          var btn = event.target;
          var div = event.target.parentElement;
          $(div).siblings(".col-nota").children(".contenuto-nota").show();
          $(btn).siblings(".fa-arrow-up").show();
          $(btn).hide();
      },

      nascondi_contenuto: function(event){
          var btn = event.target;
          var div = event.target.parentElement;
          $(div).siblings(".col-nota").children(".contenuto-nota").hide();
          $(btn).siblings(".fa-arrow-down").show();
          $(btn).hide();
      },
 
      /*edit pallino argomento*/
      open_edit_arg: function(event){
        var btn = event.target;
        $(btn).siblings(".new_pallino").show();
        $(btn).hide();
        $(btn).siblings(".edit_arg").show();
        $(btn).siblings(".chiudi_edit_arg").show();
      },

      chiudi_edit_arg: function(event){
        var btn = event.target;
        $(btn).siblings(".new_pallino").hide();
        $(btn).siblings(".new_pallino").val("");
        $(btn).hide();
        $(btn).siblings(".edit_arg").hide();
        $(btn).siblings(".open_edit_arg").show();
        
      },

      edit_arg: function(event){

        var btn = event.target;
        var arg = $(btn).parent().siblings(".col-nome-arg").children(".nome_argomento").text();
        var pallino = $(btn).siblings(".new_pallino").val();

        //alert(arg+pallino);

        axios.post("invia_dati_esame.php",
          'edit_arg=true'+
          '&argomento='+arg+
          '&pallino='+pallino+
          '&esame='+this.nome_esame
          ).then(function(response){
            //funziona solo per gli argomenti aggiunti in sessione!
            modal_esame.getArg();
        });
      }

  }
})
