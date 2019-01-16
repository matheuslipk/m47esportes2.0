<script>

function exibirModalOdds(evento){
    $.get('/evento/'+evento.id+'/odds').done(function (cat_palpites){
        var tituloModal = "<span class='text-primary'>" +evento.time1.nome + "</span> x <span class='text-danger'>" 
                + evento.time2.nome + "</span>";
        var string = "";
        var stringt1 = "";
        var stringt2 = "";

        $("#titulo-modal").html(tituloModal);            
        for(id_categoria in cat_palpites){
            if(id_categoria==1) string += montarResultadoFinal(cat_palpites[id_categoria]);
            else if(id_categoria==2) string += montarDuplaChance(cat_palpites[id_categoria]);
            else if(id_categoria==5) string += montarTotalDeGols(cat_palpites[id_categoria]);
            else if(id_categoria==6) string += montarAmbosMarcam(cat_palpites[id_categoria]);
            else if(id_categoria==14) string += montarResultFinalEAmbas(cat_palpites[id_categoria]);
            else if(id_categoria==8) string += montarPlacarExato(cat_palpites[id_categoria]);              
            else if(id_categoria==17) string += montarMargemVitoria(cat_palpites[id_categoria]);
            else if(id_categoria==15) string += montarGolsExatos(cat_palpites[id_categoria]);
            else if(id_categoria==16) string += montarGolsEAmbosMarcam(cat_palpites[id_categoria]);
            else if(id_categoria==7) string += montarParImpar(cat_palpites[id_categoria]);
            else if(id_categoria==18) string += casaMaisGols(cat_palpites[id_categoria]);
            else if(id_categoria==19) string += foraMaisGols(cat_palpites[id_categoria]);
            
            //Primeiro tempo
            else if(id_categoria==101) stringt1 += montarResultado1T(cat_palpites[id_categoria]);
            else if(id_categoria==102) stringt1 += montarDuplaChance1T(cat_palpites[id_categoria]);
            else if(id_categoria==105) stringt1 += montarTotalDeGols1T(cat_palpites[id_categoria]);
            else if(id_categoria==106) stringt1 += montarAmbosMarcam1T(cat_palpites[id_categoria]);
            else if(id_categoria==108) stringt1 += montarPlacarExato1T(cat_palpites[id_categoria]);

            //Segundo tempo
            else if(id_categoria==201) stringt2 += montarResultado2T(cat_palpites[id_categoria]);
            else if(id_categoria==206) stringt2 += montarAmbosMarcam2T(cat_palpites[id_categoria]);
            else if(id_categoria==205) stringt2 += montarTotalDeGols2T(cat_palpites[id_categoria]);
        }
        
        $("#modal-body").html(string);
        $("#modal-body1").html(stringt1);
        $("#modal-body2").html(stringt2);

        $("#modal-odds").modal();
    });
}

function exibirModalPalpites(){
    $.get('sessao/meus_palpites').done(function(palpites){
        var string = "";
        var cotaTotal=1;
        var quantPalpites = 0;
        var valorAposta = $("#valorAposta").val();

        for(index in palpites){
            evento_id = palpites[index].evento_id;
            tipo_palpite_id = palpites[index].tipo_palpite.id;

            string+="<tr>";
            string+="<td>";
            string+= "<span class='text-primary'>"+ palpites[index].evento.time1.nome + "</span> x ";
            string+= "<span class='text-danger'>"+ palpites[index].evento.time2.nome + '</span> <br>';
            string+=palpites[index].tipo_palpite.cat_palpite.nome + '<br>';
            string+= "<b>"+palpites[index].tipo_palpite.nome + '</b><br>';
            string+="</td>";
            string+="<td>";
            string+= "<span class='odd-palpite'>"+palpites[index].valor+"</span>";
            string+="<button class='btn btn-danger btn-sm btn-remove' "+
                    "onclick='removePalpite("+evento_id+", "+tipo_palpite_id+", this)'>X</button>";
            string+="</td>";
            string+="</tr>";

            cotaTotal *= palpites[index].valor;
            quantPalpites++;
        }
        if(cotaTotal>800){
            cotaTotal=800;
        }

        if(cotaTotal<2.1){
            $("#btn-fazerAposta").attr('disabled', true);
        }else{
            $("#btn-fazerAposta").attr('disabled', false);
        }

        string+="<tr>";
        string+="<td>Quant Palpites: <span id='quantPalpites'>"+quantPalpites+"</span></td>"; 
        string+="<td>Cota total: <span class='text-success' id='cotaTotal'>"+cotaTotal.toFixed(2)+"</span></td>"; 
        string+="</tr>";

        var possivelGanho = (cotaTotal.toFixed(2) * valorAposta);
        if(possivelGanho > 8000){
            possivelGanho = 8000;
        }

        string+="<tr>";
        string+="<td></td>";
        string+="<td><b>Possível ganho: <span id='possivelGanho'>R$ "+ possivelGanho.toFixed(2) +"</span></b></td>"; 
        string+="</tr>";

        $("#modal-palpites-body").html(string);
        $('#modal-palpites').modal();        
    });    
}

function atualizarPossivelGanho(){
    var cotaTotal = $("#cotaTotal").html();
    var valorAposta = $("#valorAposta").val();
    var possivelGanho = cotaTotal * valorAposta;

    if (possivelGanho >= 8000){
        possivelGanho = 8000;
    }
    if (valorAposta > 300){
        $("#valorAposta").val(300);
    }

    $("#possivelGanho").html("R$ " + possivelGanho.toFixed(2));
}

function montarResultadoFinal(odds){
    return montarCatGenerico(odds, 3);
}
function montarDuplaChance(odds){
    return montarCatGenerico(odds, 3);
}
function montarAmbosMarcam(odds){
    return montarCatGenerico(odds, 2);
}
function montarTotalDeGols(odds){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+odds[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    if(odds[0] != null && odds[5] !=null){
        string+=getLinha2(odds[0], odds[5]);
    }
    if(odds[1] != null && odds[6] !=null){
        string+=getLinha2(odds[1], odds[6]);
    }
    if(odds[2] != null && odds[7] !=null){
        string+=getLinha2(odds[2], odds[7]);
    }
    if(odds[3] != null && odds[8] !=null){
        string+=getLinha2(odds[3], odds[8]);
    }
    if(odds[4] != null && odds[9] !=null){
        string+=getLinha2(odds[4], odds[9]);
    }
    
    return string;
}
function montarPlacarExato(odds){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+odds[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";



    //Inicio Casa
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-primary' data-toggle='dropdown'>";
    string+= "Casa";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 > golsT2){            
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";




    //Inicio Empate
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn' data-toggle='dropdown'>";
    string+= "Empate";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 == golsT2){
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";




    //Inicio Fora
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-danger' data-toggle='dropdown'>";
    string+= "Fora";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 < golsT2){
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
     string+="</td>";


    string+="</tr>";
    return string;
}
function montarResultFinalEAmbas(categoria){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+categoria[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    for(id_odds in categoria){
        if(id_odds>=3)break;
        string += "<td colspan='4'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";            
    }
    string+="</tr>";

    string+="<tr>";
    for(id_odds in categoria){
        if(id_odds<3)continue;
        string += "<td colspan='4'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";            
    }
    string+="</tr>";

    return string;
}
function montarMargemVitoria(odds){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+odds[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";


    //Inicio Casa
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-primary' data-toggle='dropdown'>";
    string+= "Casa";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var tipoPalpite = odds[id_odds].tipo_palpite.id;

        if(tipoPalpite >= 80 && tipoPalpite <=83){            
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";




    //Inicio Empate
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn' data-toggle='dropdown'>";
    string+= "Empate";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var tipoPalpite = odds[id_odds].tipo_palpite.id;

        if(tipoPalpite >= 88 && tipoPalpite <=89){            
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";



    //Inicio Fora
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-danger' data-toggle='dropdown'>";
    string+= "Fora";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var tipoPalpite = odds[id_odds].tipo_palpite.id;

        if(tipoPalpite >= 84 && tipoPalpite <=87){            
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";


    string+="</tr>";
    return string;
}
function montarGolsExatos(categoria){
    return montarCatGenerico(categoria, 4);
}
function montarGolsEAmbosMarcam(categoria){
    return montarCatGenerico(categoria, 2);
}
function montarParImpar(categoria){
    return montarCatGenerico(categoria, 2);
}
function casaMaisGols(categoria){
    return montarCatGenerico(categoria, 3);
}
function foraMaisGols(categoria){
    return montarCatGenerico(categoria, 3);
}

//Primeiro Tempo
function montarResultado1T(odds){
    return montarCatGenerico(odds, 3);
} 
function montarAmbosMarcam1T(odds){
    return montarCatGenerico(odds, 2);
}
function montarDuplaChance1T(odds){
    return montarCatGenerico(odds, 3);
}
function montarTotalDeGols1T(odds){
    return montarCatGenerico(odds, 2);
}
function montarPlacarExato1T(odds){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+odds[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";



    //Inicio Casa
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-primary' data-toggle='dropdown'>";
    string+= "Casa";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 > golsT2){            
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";




    //Inicio Empate
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn' data-toggle='dropdown'>";
    string+= "Empate";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 == golsT2){
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
    string+="</td>";




    //Inicio Fora
    string+="<td colspan='4'>";
    string+= "<div class='btn-group'>";//Inicio btn-group
    string+= "<button class='btn btn-danger' data-toggle='dropdown'>";
    string+= "Fora";
    string+= "</button>";
    string+= "<div class='dropdown-menu'>";//Inicio dropdown-menu

    for(id_odds in odds){
        var strTipoPalpite = (odds[id_odds].tipo_palpite.id).toString();
        var golsT1 = strTipoPalpite.substr(1,1);
        var golsT2 = strTipoPalpite.substr(2,1);  

        if(golsT1 < golsT2){
            string+= botaopalpite(odds[id_odds]) + ' ';
            string+= odds[id_odds].tipo_palpite.nome + '<br>';
        }            
    }
    string+= "</div>";//Fim dropdown-menu
    string+= "</div>";//Fim btn-group
     string+="</td>";


    string+="</tr>";
    return string;
}


//Segundo Tempo
function montarResultado2T(odds){
    return montarCatGenerico(odds, 3);
}
function montarAmbosMarcam2T(odds){
    return montarCatGenerico(odds, 2);
}
function montarTotalDeGols2T(odds){
    return montarCatGenerico(odds, 2);
}

function montarCatGenerico(categoria, quantCol){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+categoria[0].cat_palpite.nome+"</td></tr>";
    if(quantCol<2) quantCol = 2;
    if(quantCol>4) quantCol = 4;

    for(id_odds in categoria){
        if(id_odds % quantCol == 0){
            string+="<tr>";
        }
        string += "<td colspan='"+ (12/quantCol) +"'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";     

       if(id_odds % quantCol == (quantCol-1)){
            string+="</tr>";
        }
    }

    return string;
}


function botaopalpite(palpite) {
    var btn="";
    if(palpite.selecionado != null){
        btn+="<button class='btn btn-info btn-sm btn-danger' data-evento='"+ palpite.evento_id +"' data-palpite='"+ palpite.tipo_palpite_id +"' onclick='enviarPalpite(this)'>";
    }else{
        btn+="<button class='btn btn-info btn-sm' data-evento='"+ palpite.evento_id +"' data-palpite='"+ palpite.tipo_palpite_id +"' onclick='enviarPalpite(this)'>";
    }
    
    btn+=palpite.valor;
    btn+="</button>";
    return btn;
}
function enviarPalpite(btn){
    var evento_id = $(btn).attr('data-evento');
    var tipo_palpite_id = $(btn).attr('data-palpite');
    var tipoAcao="";

    if($(btn).hasClass('btn-danger')){
        removePalpite(evento_id, tipo_palpite_id);
    }else{
        addPalpite(evento_id, tipo_palpite_id);        
    }   
}
function addPalpite(evento_id, tipo_palpite_id) {
    $.get("/sessao/palpite/"+evento_id+"/"+tipo_palpite_id, {
        acao : 'add'
    }).done(function (data){
        if(data.sucesso==false){
            alert(data.erro);
            return;
        }
        $("button[data-evento='"+evento_id+"']").removeClass('btn-danger');
        $("button[data-evento='"+evento_id+"'][data-palpite='"+tipo_palpite_id+"']").addClass('btn-danger');
    });
}
function removePalpite(evento_id, tipo_palpite_id, btnRemove) {
    $.get("/sessao/palpite/"+evento_id+"/"+tipo_palpite_id, {
        acao : 'remove'
    }).done(function (data){
        $("button[data-evento='"+evento_id+"']").removeClass('btn-danger');     
        if(typeof(btnRemove) !="undefined"){
            cotaPalpiteExcluido = $(btnRemove).parent().find(".odd-palpite").html();
            quantPalpites = $("#quantPalpites").html();

            $(btnRemove).parent().parent().remove();
            cotaTotal = $("#cotaTotal").html();
            cotaTotal /= cotaPalpiteExcluido;
            $("#cotaTotal").html(cotaTotal.toFixed(2));
            $("#quantPalpites").html((quantPalpites-1));

            var cotaTotal = $("#cotaTotal").html();
            if(cotaTotal<2.1){
                $("#btn-fazerAposta").attr('disabled', true);
            }else{
                $("#btn-fazerAposta").attr('disabled', false);
            }
        }
    });
}


function getLinha2(odd1, odd2){
    var linha="";
    linha+="<tr>";

    linha+="<td colspan='6'>";
    linha+= odd1.tipo_palpite.nome + "<br>";
    linha+= botaopalpite(odd1);
    linha+="</td>";

    linha+="<td colspan='6'>";
    linha+= odd2.tipo_palpite.nome + "<br>";
    linha+= botaopalpite(odd2);
    linha+="</td>";
    linha+="</tr>";

    return linha;
}    

function fazerAposta(){
    $.get('/aposta/fazerAposta', {
        valorAposta: $('#valorAposta').val(),
        nomeAposta: $('#nomeAposta').val()
    }).done(function(data){
        
    });
}


</script>