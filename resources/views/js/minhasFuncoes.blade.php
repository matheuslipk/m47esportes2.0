<script>

function exibirModalOdds(evento){
    $.get('/evento/'+evento.id+'/odds').done(function (cat_palpites){
        var tituloModal = "<span class='text-primary'>" +evento.time1.nome + "</span> x <span class='text-danger'>" 
                + evento.time2.nome + "</span>";
        var string = "";
        $("#titulo-modal").html(tituloModal);            
        for(id_categoria in cat_palpites){
            if(id_categoria==1) string += montarResultadoFinal(cat_palpites[id_categoria]);
            else if(id_categoria==2) string += montarDuplaChance(cat_palpites[id_categoria]);
            else if(id_categoria==5) string += montarTotalDeGols(cat_palpites[id_categoria]);
            else if(id_categoria==6) string += montarAmbosMarcam(cat_palpites[id_categoria]);
            else if(id_categoria==14) string += montarResultFinalEAmbas(cat_palpites[id_categoria]);                
        }
        
        $("#modal-body").html(string);
        $("#modal-odds").modal();
    });
}


function montarResultadoFinal(categoria){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+categoria[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    for(id_odds in categoria){
        string += "<td colspan='4'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";
    }
    string+="</tr>";
    return string;
}
function montarDuplaChance(categoria){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+categoria[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    for(id_odds in categoria){
        string += "<td colspan='4'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";
    }
    string+="</tr>";
    return string;
}
function montarAmbosMarcam(categoria){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+categoria[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    for(id_odds in categoria){
        string += "<td colspan='6'>";
        string += categoria[id_odds].tipo_palpite.nome + '<br>';
        string += botaopalpite(categoria[id_odds]);
        string += "</td>";
    }
    string+="</tr>";
    return string;
}

function montarTotalDeGols(odds){
    var string="";
    string+="<tr class='cat_palpite'><td colspan='12'>"+odds[0].cat_palpite.nome+"</td></tr>";
    string+="<tr>";
    string+=getLinha2(odds[0], odds[5]);
    string+=getLinha2(odds[1], odds[6]);
    string+=getLinha2(odds[2], odds[7]);
    string+=getLinha2(odds[3], odds[8]);
    string+=getLinha2(odds[4], odds[9]);
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

function botaopalpite(palpite) {
    var btn="";
    btn+="<button class='btn btn-info' onclick='enviarPalpite("+palpite.evento_id+", "+palpite.tipo_palpite_id+")'>";
    btn+=palpite.valor;
    btn+="</button>";
    return btn;
}

function enviarPalpite(evento_id, odds_id){
    $.get("/sessao/palpite/"+evento_id+"/"+odds_id);
}

function getLinha2(odd1, odd2){
    var linha="";
    linha+="<tr>";

    linha+="<td colspan='4'>";
    linha+= odd1.tipo_palpite.nome + "<br>";
    linha+= botaopalpite(odd1);
    linha+="</td>";

    linha+="<td colspan='4'>";
    linha+= odd2.tipo_palpite.nome + "<br>";
    linha+= botaopalpite(odd2);
    linha+="</td>";

    linha+="</tr>";



    return linha;
}    


</script>