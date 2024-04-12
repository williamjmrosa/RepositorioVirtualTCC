// Função para carregar o menu de login
$("#login").load("../menuLogin/menuLogin.php");

function verTCC(idTCC) {

    var parametros = {idTCC: idTCC};

    $.ajax({
        url: '../visao/lerMarcado.php',
        method: 'POST',
        data: parametros,
        dataType: 'json',
        success: function (response) {
            //response = JSON.parse(response);
            if(response.sucesso){
                tcc = response.sucesso;

                $("#tccSelecionado").find('embed').attr('src', tcc.localPDF);
                $("#favoritos").attr("onclick","removerFavorito(this,"+tcc.idTCC+")");
                $("#btn-indicar").attr("onclick","clicarIndicar(this,"+tcc.idTCC+")");
                $("#titulo-tcc").html(tcc.titulo);
                $("#btns-tcc").removeClass("d-none");
                $("#btns-tcc").addClass("row container");

            }else{
                alert(response.erro);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Acessar informações sobre o erro
            console.log("Erro na requisição AJAX: "+textStatus);
            
            // Verificar se o erro é um erro customizado
            if(jqXHR.responseText) {
                console.log("Erro customizado: "+jqXHR.responseText);
            }
        }

    });

}

$(document).ready(function () {

    // carregar TCC selecionado
    $("#tcclista").on("click", function () {
        var selected = $(this).find("option:selected").val();
        console.log(selected);
        verTCC(selected);

    });

});