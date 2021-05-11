$(document).ready(function(){
            
    /* edit button*/ 

    function modifica_esame(){
        var testo = $(this).text();
        if (testo == "Edit"){
            $(this).parent().parent().height("200px");
            $(this).text("Chiudi");
        }

        else if (testo == "Chiudi"){
            $(this).parent().parent().height("30px");
            $(this).text("Edit");
        }
    }

    $(".btn-edit").click(modifica_esame);
    
    
    /* rimuovi esame button*/ 
    //todo lanciare un alert "sei sicuro di voler rimuovere un esame e tuttti i suoi contenuti ?"

    
    function rimuovi_esame(){
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
    }
    $(".btn-rimuovi").click(rimuovi_esame);

    /*aggiungi esame button */
    $("#btn-esame").click(function apri_form_esame(){
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
                                '<td class="btn-e"><button class="btn-edit">Edit</button></td>'+
                                '<td class="btn-r"><button class="btn-rimuovi">Rimuovi</button></td>'+
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
                                '<td class="btn-e"><button class="btn-edit">Edit</button></td>'+
                                '<td class="btn-r"><button class="btn-rimuovi">Rimuovi</button></td>'+
                                '</tr>');
                }
                $("#table_body").children(":first").children(".btn-e").children().click(modifica_esame);
                $("#table_body").children(":first").children(".btn-r").children().click(rimuovi_esame);
            },
            error: function(msg){
                alert("errore");
            } 
        });
        
        $(this).siblings("input[target=nome]").val('');
        $(this).siblings("input[target=voto]").val('');
        $(this).siblings("input[target=cfu]").val('');

        
    });

    

    
        
});