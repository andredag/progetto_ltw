$(document).ready(function(){
            
    //se utente clicca sostenuto nel modal aggiungi esame 
    //viene visualizzato voto e data sostenuto
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
    //aggancio al click di aggiungi-btn l'evento "apro modal aggi8ungi esame"
    $("#aggiungi-btn").click(function(){
        $("#modal-aggiungi").modal('show');
    })
    //funzione per pulire il modal dopo la sua chiusura 
    function undo_dopo_exit(){
        $(".modal-body input[target=nome]").val('');
        $(".modal-body input[target=voto]").val('');
        $(".modal-body input[target=cfu]").val('');
        $(".modal-body input[target=data_sostenuto]").val('');
        $("#flexCheckDefault").attr('checked',false);
        $("#voto_info").attr("hidden",true);
        $("#data_sostenuto").attr("hidden",true);
        $("#alert").fadeOut();
    }
    //aggancio alla chiusura del modal aggiungi esame l'evento "chiudi modal" e pulisce modal
    $("#btn-close").click(function(){
        $("#modal-aggiungi").modal('hide');
        undo_dopo_exit();
        }
    )


    //funzione che chiude il modal esame
    function chiudi_modal_esame(){
        $("#modal-esame").modal('hide');
    }

    $(".close_modal").click(chiudi_modal_esame);

    

    /* ok form button invia dati al server per aggiungere l'esame e aggiunge l'html per l'esame*/
    $("#ok-btn").click(function ok_form_esame(){
        $("#alert").text("");
        var errore=false;
        var nome_esame = $(this).siblings("input[target=nome]").val();
        var voto = $(this).siblings("input[target=voto]").val();
        var cfu = $(this).siblings("input[target=cfu]").val();
        var data_sostenuto = $(this).siblings("input[target=data_sostenuto]").val(); 
        var sostenuto = $("#flexCheckDefault").prop("checked");
        if(nome_esame=='') {
            $("#alert").append("Nome non corretto"+'</br>');
            errore=true;
        }
        if(isNaN(cfu) || cfu<0 || cfu==''){
            $("#alert").append("Cfu non corretto"+'</br>');
            errore=true;
        }
        if((sostenuto==true && isNaN(voto))||(sostenuto==true &&voto=="")){
            $("#alert").append("Inserisci un numero corretto nel campo voto"+'</br>');
            errore=true;
        }
        if(sostenuto==true && !isNaN(voto)){
            if(voto<0 || voto>31){
                $("#alert").append("Inserisci un numero corretto nel campo voto compreso tra 0 e 31 ");
                errore=true;
            }
        }
        if(sostenuto==true && data_sostenuto==""){
            $("#alert").append("Inserisci la data in cui hai sostenuto l'esame"+'</br>');
            errore=true;
        }

        if(errore==true) {
            $("#alert").fadeIn("slow");
            return false;
        }

        
        $("#modal-aggiungi").modal('hide');
        $("#alert").fadeOut();
        $.ajax({
            type: "POST",
            url: "./invia_dati_esame.php",
            data: "nome_esame="+nome_esame+
                  "&voto="+voto+
                  "&cfu="+cfu+
                  "&sostenuto="+sostenuto+
                  "&data_sostenuto="+data_sostenuto+
                  "&add=true",
            dataType: "html" ,
            success: function(msg){
                
                if(sostenuto==true){
                $("#table_body").prepend('<tr class="riga_tabella"> '
                                +'<td></td>'+
                                '<td class="nome_esame">'+nome_esame+'</td>'+
                                '<td>'+
                                    '<i class="fas fa-check-circle"> </i>'+
                                '</td>'+
                                '<td >'+voto+'</td>'+
                                '<td >'+cfu+'</td>'+
                                '<td class="btn-e"><i class="fas fa-edit"></i></td>'+
                                '<td class="btn-r"><i class="fas fa-trash-alt"></i></td>'+
                                '</tr>');
                }
                else {
                    $("#table_body").prepend('<tr class="riga_tabella"> '
                                +'<td></td>'+
                                '<td class="nome_esame">'+nome_esame+'</td>'+
                                '<td>'+
                                '<i class="far fa-times-circle"></i>'+
                                '</td>'+
                                '<td >'+voto+'</td>'+
                                '<td >'+cfu+'</td>'+
                                '<td class="btn-e"><i class="fas fa-edit"></i></td>'+
                                '<td class="btn-r"><i class="fas fa-trash-alt"></i></td>'+
                                '</tr>');
                }
                $("#table_body").children(":first").children(".btn-e").children().click(tab_esame.apri_modal_esame);
                $("#table_body").children(":first").children(".btn-r").children().click(tab_esame.rimuovi_esame);
            },
            error: function(msg){
                alert("errore");
            } 
        });
        undo_dopo_exit();

        //aggiorna i grafici e i numeri vicino.
        $.getScript("./chart.js");
        scrivi_numeri();

    });

    //funzione per aggiornare media a/p cfu e num esame nella sezione statistiche
    function scrivi_numeri(){  
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
    };

    // scrive nei box di stat i valori di media a/p, cfu, numero esami
    scrivi_numeri();
    
    $("#form_prog").hide();
    $("#form_note").hide();
    $("#form_link").hide();
    
    // mostra campi per aggiungere un argomento
    $("#btn-arg").click(function(){
        $("#form_prog").show();
        $(this).hide(); 
    });
    
    //esce dalla parte per aggiungere un argomento, pulendo i campi
    $("#exit-arg").click(function(){
        $("#form_prog").hide();
        $("#form_prog input").val('');
        $("#form_prog select").val('');
        $("#btn-arg").show();
    });

    // mostra campi per aggiungere un link
    $("#btn-link").click(function(){
        $("#form_link").show();
        $(this).hide(); 
    });

    //esce dalla parte per aggiungere un link, pulendo i campi
    $("#exit-link").click(function(){
        $("#form_link").hide();
        $("#form_link input").val('');
        $("#btn-link").show();
    });

    // mostra campi per aggiungere una nota
    $("#btn-nota").click(function(){
        $("#form_note").show();
        $(this).hide(); 
    });
    
    //esce dalla parte per aggiungere una nota, pulendo i campi
    $("#exit-nota").click(function(){
        $("#form_note").hide();
        $("#form_note textarea").val('');
        $("#form_note input[target=descrizione]").val('');
        $("#btn-nota").show();
    });
    
    // quando si chiude il modal esame vengono nascoste tutte le form
    // e vengono puliti tutti i campi
    $("#exit_modal_esame").click(function(){
        $("#exit-nota").click();
        $("#exit-link").click();
        $("#exit-arg").click();
    });


   
});