// Função para carregar o menu de login
$("#login").load("../menuLogin/menuLogin.php");

//$("#divCampus").load("../visao/selectTCCPgMarcado.php?div=divCampus&tipoCheck=favorito");
//$("#divCurso").load("../visao/selectTCCPgMarcado.php?div=divCurso&tipoCheck=favorito");


var idInstituicao = null;
var idCurso = null;

// Ver TCC Favorito selecionado
function verTCC(option, idTCC = null) {

    if (idTCC == null) {
        var idTCC = $(option).val();
    }

    var parametros = { idTCC: idTCC };

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
                
                if(idInstituicao != null && idCurso != null) {
                    var botao = $("#excluirIndicacaoTCC");
                    botao.val(idTCC+" "+idInstituicao+" "+idCurso);
                    botao.removeAttr("disabled");
                }else{
                    var botao = $("#excluirIndicacaoTCC");
                    botao.val("");
                    botao.attr("disabled", "disabled");
                }

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
        $("#divListarAlunoIndicados").load("../visao/selectTCCPgMarcado.php?div=divListarAlunoIndicado&tipoCheck=" + radioMarcado);
        $("#divTCCIndicadoParaAluno").load("../visao/selectTCCPgMarcado.php?div=divTCCIndicadoParaAluno&tipoCheck=" + radioMarcado);
        $("#favoritos").addClass("d-none");
        $("#btn-indicar").addClass("d-none");
    } else if (radioMarcado == "favorito") {
        $("#divTCC").load("../visao/selectTCCPgMarcado.php?tipoCheck=" + radioMarcado + "&div=divTCC");
        $("#divCampus").load("../visao/selectTCCPgMarcado.php?div=divCampus&tipoCheck=" + radioMarcado);
        $("#divCurso").load("../visao/selectTCCPgMarcado.php?div=divCurso&tipoCheck=" + radioMarcado);
        $("#divProfessor").empty();
        $("#divListarAlunoIndicados").empty();
        $("#divTCCIndicadoParaAluno").empty();
        $("#favoritos").removeClass("d-none");
        $("#btn-indicar").removeClass("d-none");
        $("#btns-tcc").addClass("d-none");
    } else {
        //alert("Erro");
        $("#checkFavorito").prop("checked", true);
        verificarOpcaoSelecionada();
    }

}

//verificar Qual lista de tcc foi selecionado Favoritos ou Indicados ao carregar a pagina
verificarOpcaoSelecionada();

//Gerar as opções de curso, professor e tccs para instituicao escolhida
function atualizarDivInstituicao(div) {
    //console.log($(div).val());
    var radioMarcado = $("input[name='checkTipo']:checked").val();

    if ($(div).val() != null) {
        idInstituicao = $(div).val();
        idCurso = null;
    } else {
        idInstituicao = null;
    }
    //Valores para serem passados como parametros
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divCurso"
    });


    //Carregar as opções de Cursos da instituicao escolhida
    $("#divCurso").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Valores para serem passados como parametro
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divTCC"
    });

    //Carregar as opções de TCCs da instituicao escolhida
    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Valores para serem passados como parametro
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divProfessor"
    });

    //Carregar as opções de Professores da instituicao escolhida
    $("#divProfessor").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Inativar o botão de excluir indicação
    var botao = $("#excluirIndicacaoTCC");
    botao.val("");
    botao.attr("disabled", "disabled");

}

//Gerar as opções de curso, professor e tccs para o curso escolhido
function atualizarDivCurso(div) {

    idCurso = $(div).val();
    //console.log(idCurso, idInstituicao);
    var radioMarcado = $("input[name='checkTipo']:checked").val();

    if ($(div).val() != null) {
        idCurso = $(div).val();
    } else {
        idCurso = null;
    }

    //Valores para serem passados como parametro
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divTCC"
    });

    //Carregar as opções de TCCs do curso escolhido
    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Valores para serem passados como parametro
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        div: "divProfessor"
    });

    //Carregar as opções de Professores do curso escolhido
    $("#divProfessor").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Inativar o botão de excluir indicação
    var botao = $("#excluirIndicacaoTCC");
    botao.val("");
    botao.attr("disabled", "disabled");

}

//Gerar as opções de professor e tccs para o professor escolhido
function atualizarDivProfessor(div) {

    //Matricula do professor selecionado
    var matricula = $(div).val();

    var radioMarcado = $("input[name='checkTipo']:checked").val();

    //Valores para serem passados como parametro
    var parametros = $.param({
        idInstituicao: idInstituicao,
        idCurso: idCurso,
        tipoCheck: radioMarcado,
        matricula: matricula,
        div: "divTCC"
    });

    //Carregar as opções de TCCs do professor escolhido
    $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);

    //Inativar o botão de excluir indicação
    var botao = $("#excluirIndicacaoTCC");
    botao.val("");
    botao.attr("disabled", "disabled");

}

//Gerar as opções de tccs para o aluno escolhido
function atualizarDivTCCIndicadoParaAluno(div) {
    //Matricula do Aluno selecionado
    var matricula = $(div).val();
    var radioMarcado = $("input[name='checkTipo']:checked").val();

    //Valores para serem passados como parametro
    var parametros = $.param({ 
        tipoCheck: radioMarcado, 
        matricula: matricula,
        div: "divTCCIndicadoParaAluno"
    });

    //Carregar as opções de TCCs do Aluno escolhido
    $("#divTCCIndicadoParaAluno").load("../visao/selectTCCPgMarcado.php?" + parametros);

}

//Gerar o idIndicaAluno do TCC selecionado e ativar o botão para excluir
function gerarIdIndicaAluno(div) {

    //idIndicaAluno
    var idIndicaAluno = $(div).val();

    //Ativar o botão para excluir indicação aluno
    var botao = $("#excluirIndicacaoAluno");
    botao.val(idIndicaAluno);
    botao.removeAttr("disabled");

    console.log(idIndicaAluno);

}

//Excluir a indicação do TCC selecionado
function excluirIndicacaoTCC(div) {
    var ids = $(div).val();
    var radioMarcado = $("input[name='checkTipo']:checked").val();

    ids = ids.split(" ");

    $.ajax({
        url: '../controle/indicar-controle.php?OP=2',
        type: 'POST',
        data: {
            idTCC: ids[0],
            idInstituicao: ids[1],
            idCurso: ids[2]
        },
        success: function(response) {
            var resposta = JSON.parse(response);
            // Verificar se a resposta do servidor é do formato esperado
            if (resposta && resposta.sucesso) {
                //console.log(resposta.sucesso);
                alert(resposta.sucesso);
                
                var parametros = $.param({
                    idTCC: ids[0],
                    idInstituicao: ids[1],
                    idCurso: ids[2],
                    tipoCheck: radioMarcado,
                    div: "divTCC"
                });
            
                $("#divTCC").load("../visao/selectTCCPgMarcado.php?" + parametros);
                
            }else{
                alert(resposta.erro);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Acessar informações sobre o erro
            console.log("Erro na requisição AJAX: " + textStatus);

            // Verificar se o erro é um erro customizado
            if (jqXHR.responseText) {
                console.log("Erro customizado: " + jqXHR.responseText);
            }
        }
    });
}

//Excluir a indicação do Aluno selecionado
function excluirIndicacaoAluno(div) {
    var idIndicaAluno = $(div).val();

    var radioMarcado = $("input[name='checkTipo']:checked").val();

    $.ajax({
        url: '../controle/indicar-controle.php?OP=4',
        type: 'POST',
        data: {
            OP: 4,
            idIndicacaoAluno: idIndicaAluno
        },
        success: function(response) {
            var resposta = JSON.parse(response);
            // Verificar se a resposta do servidor é do formato esperado
            if (resposta && resposta.sucesso) {
                //console.log(resposta.sucesso);
                alert(resposta.sucesso);
                var matricula = $("#tccIndicadoAluno").val();

                $("#divListarAlunoIndicados").load("../visao/selectTCCPgMarcado.php?div=divListarAlunoIndicado&tipoCheck=" + radioMarcado, function () {
                    //var select = $("#tccIndicadoAluno");
                    //select.val(matricula);

                    //select.trigger("change");
                    // Obter a opção pelo seu ID ou seletor
                    var option = $("#tccIndicadoAluno option[value='" + matricula + "']");
                    
                    // Verificar se a opção existe
                    if (option.length > 0) {
                        // Se a opção existir, selecioná-la
                        option.prop("selected", true);
                    
                        // Disparar o evento 'change'
                        option.trigger("change");
                    }else{
                        $("#divTCCIndicadoParaAluno").empty();
                    }
                });
            } else {
                alert(resposta.erro);
                //console.log(resposta.erro);
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
    //Desativar o botão para excluir indicação aluno do tcc
    $(div).attr("disabled", true);
}

$(document).ready(function () {

    // carregar TCC selecionado
    $("#tcclista").click(function () {
       

    });
    //Carregas as opções dos selects de acordo com o tipo selecionado 
    $("input[name='checkTipo']").on("change", function () {
        verificarOpcaoSelecionada();
    });

});