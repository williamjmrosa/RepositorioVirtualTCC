// Função para carregar o menu de login
$("#login").load("../menuLogin/menuLogin.php");

//$("#divCampus").load("../visao/selectTCCPgMarcado.php?div=divCampus&tipoCheck=favorito");
//$("#divCurso").load("../visao/selectTCCPgMarcado.php?div=divCurso&tipoCheck=favorito");


var idInstituicao = null;
var idCurso = null;

// Ver TCC Favorito selecionado
function verTCC(option) {



    var parametros = { idTCC: $(option).val() };

    $.ajax({
        url: '../visao/lerMarcado.php',
        method: 'POST',
        data: parametros,
        dataType: 'json',
        success: function (response) {
            //response = JSON.parse(response);
            if (response.sucesso) {
                tcc = response.sucesso;

                $("#tccSelecionado").find('embed').attr('src', tcc.localPDF);
                $("#favoritos").attr("onclick", "removerFavorito(this," + tcc.idTCC + ")");
                $("#btn-indicar").attr("onclick", "clicarIndicar(this," + tcc.idTCC + ")");
                $("#titulo-tcc").html(tcc.titulo);
                $("#btns-tcc").removeClass("d-none");
                $("#btns-tcc").addClass("row container");

            } else {
                alert(response.erro);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Acessar informações sobre o erro
            console.log("Erro na requisição AJAX: " + textStatus);

            // Verificar se o erro é um erro customizado
            if (jqXHR.responseText) {
                console.log("Erro customizado: " + jqXHR.responseText);
            }
        }

    });

}

//verificar Qual lista de tcc foi selecionado Favoritos ou Indicados
function verificarOpcaoSelecionada() {

    var radioMarcado = $("input[name='checkTipo']:checked").val();
    if (radioMarcado == "indicado") {
        $("#divTCC").load("../visao/selectTCCPgMarcado.php?tipoCheck=" + radioMarcado + "&div=divTCC");
        $("#divCampus").load("../visao/selectTCCPgMarcado.php?div=divCampus&tipoCheck=" + radioMarcado);
        $("#divCurso").load("../visao/selectTCCPgMarcado.php?div=divCurso&tipoCheck=" + radioMarcado);
        $("#divProfessor").load("../visao/selectTCCPgMarcado.php?div=divProfessor&tipoCheck=" + radioMarcado);
        $("#favoritos").addClass("d-none");
        $("#btn-indicar").addClass("d-none");
    } else if (radioMarcado == "favorito") {
        $("#divTCC").load("../visao/selectTCCPgMarcado.php?tipoCheck=" + radioMarcado + "&div=divTCC");
        $("#divCampus").load("../visao/selectTCCPgMarcado.php?div=divCampus&tipoCheck=" + radioMarcado);
        $("#divCurso").load("../visao/selectTCCPgMarcado.php?div=divCurso&tipoCheck=" + radioMarcado);
        $("#divProfessor").empty();
        $("#favoritos").removeClass("d-none");
        $("#btn-indicar").removeClass("d-none");
        $("#btns-tcc").addClass("d-none");
    } else {
        //alert("Erro");
        $("#checkFavorito").prop("checked", true);
        verificarOpcaoSelecionada();
    }

}

verificarOpcaoSelecionada();

function atualizarDivInstituicao(div) {
    console.log($(div).val());
    var radioMarcado = $("input[name='checkTipo']:checked").val();

    if ($(div).val() != "") {
        idInstituicao = $(div).val();
        idCurso = null;
    } else {
        idInstituicao = null;
    }

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divCurso"
    });



    $("#divCurso").load("../visao/selectTCCPgMarcado.php?" + parametros);

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divTCC"
    });


    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divProfessor"
    });

    $("#divProfessor").load("../visao/selectTCCPgMarcado.php?" + parametros);

}

function atualizarDivCurso(div) {

    idCurso = $(div).val();

    var radioMarcado = $("input[name='checkTipo']:checked").val();

    if ($(div).val() != "") {
        idCurso = $(div).val();
    } else {
        idCurso = null;
    }

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divTCC"
    });


    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divProfessor"
    });

    $("#divProfessor").load("../visao/selectTCCPgMarcado.php?" + parametros);

}

function atualizarDivProfessor(div) {

    var matricula = $(div).val();

    var radioMarcado = $("input[name='checkTipo']:checked").val();

    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        matricula: matricula,
        div: "divTCC"
    });

    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

}

$(document).ready(function () {

    // carregar TCC selecionado
    $("#tcclista").click(function () {
       

    });

    $("input[name='checkTipo']").on("change", function () {
        verificarOpcaoSelecionada();
    });

});