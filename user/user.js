$(document).ready(function(){
            
    
    
    function chiudi_modal_esame(){
        $("#exampleModalScrollable").modal('hide');
    }

    $(".close_modal").click(chiudi_modal_esame);

    /* rimuovi esame button*/ 
    //todo lanciare un alert "sei sicuro di voler rimuovere un esame e tuttti i suoi contenuti ?"

    
    /*function rimuovi_esame(){
        $(this).parent().parent().remove();
        
        var nome_esame = $(this).parent().siblings(".nome_esame").text();
        
        
        $.ajax({
            type: "POST",
            url: "./invia_dati_esame.php",
            data: "nome_esame="+nome_esame+"&remove=true",
            dataType: "html" ,
            success: function(msg){
                
                alert("esame rimosso");
            },
            error: function(msg){
                alert("errore");
            },
            beforeSend: function(){
                alert("sei sicuro coglione?");
            }
        });
        //aggiorna i grafici e i numeri vicino.
        $.getScript("./chart.js");
        scrivi_numeri();
    }
    $(".fa-trash-alt").click(rimuovi_esame);*/

    /*aggiungi esame button */
    $("#aggiungi-btn").click(function apri_form_esame(){
        $(".info-esame").show();
    });

    /* esci form button*/
    $("#btn-close").click(function chiudi_form_esame(){
        $(".info-esame").hide();
    });

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

        
        $(".info-esame").hide();
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
                alert(msg);
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
        
        $(this).siblings("input[target=nome]").val('');
        $(this).siblings("input[target=voto]").val('');
        $(this).siblings("input[target=cfu]").val('');
        $(this).siblings("input[target=data_sostenuto]").val('');

        //aggiorna i grafici e i numeri vicino.
        $.getScript("./chart.js");
        scrivi_numeri();
    });

       
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

    scrivi_numeri();
   

        
});