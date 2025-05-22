// Carrega o menu de login
$("#login").load("../menuLogin/menuLogin.php");

// Função para limpar o formulário de endereço
function limpa_formulário_cep() {
    // Limpa valores do formulário de cep.
    $("#rua").val("");
    $("#bairro").val("");
    $("#cidade").val("");
    $("#uf").val("");
    $("#ibge").val("");
}
// Função para mostrar a senha e esconder
function mostrarSenha(input) {
    var inputSenha = $("#senha");
    var btnSenha = $(input);
    if ($(inputSenha).attr("type") == "password") {
        inputSenha.attr("type", "text");
        $(btnSenha).removeClass("bi-eye-fill");
        $(btnSenha).addClass("bi-eye-slash-fill");
    } else {
        inputSenha.attr("type", "password");
        $(btnSenha).removeClass("bi-eye-slash-fill");
        $(btnSenha).addClass("bi-eye-fill");
    }
}

// Função para carregar o curso do aluno ao selecionar o campus
function campusSelecionado(option, callback) {
    var select = $(option);
    var valor = select.val();
    $('#curso').load('../visao/selecteCurso.php?ID=' + valor, function () {
        callback();
    });

}

// Função para completar o formulário de alteração
function completeForm(entrada) {
    var usuario = entrada;

    $("#nome").val(usuario.nome);
    $("#cpf").val(usuario.cpf).unmask().mask('000.000.000-00');
    $("#rg").val(usuario.rg).unmask().mask('99.999.999-9');
    $("#email").val(usuario.email);
    $("#cep").val(usuario.end.cep);
    $("#rua").val(usuario.end.logradouro.split(",")[0].trim());
    $("#numero").val(usuario.end.logradouro.replace(/^.*?,\s*/, ""));
    $("#complemento").val(usuario.end.complemento);
    $("#bairro").val(usuario.end.bairro);
    $("#cidade").val(usuario.end.cidade);
    $("#uf").val(usuario.end.uf);
    $("#telefone").val(usuario.telefone).unmask().mask('(00) 00000 - 0000');

    $("input[name=matricula]").remove();

    $(".cadastro form").append('<input type="hidden" name="matricula" value="' + usuario.matricula + '"/>');

    $(".cadastro form").append('<input type="hidden" name="idEndereco" value="' + usuario.end.idEndereco + '"/>');

}

$(document).ready(function () {

    //Mascara para o campo CPF
    $("#cpf").mask('000.000.000-00');
    //Mascara para o campo Telefone
    $("#telefone").mask('(00) 00000 - 0000');
    //Mascara para o campo CEP
    $("#cep").mask('00000-000');
    //Mascara para o campo RG
    $('#rg').mask('99.999.999-99');

    //Quando o campo cep perde o foco.
    $("#cep").blur(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#rua").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#uf").val("...");
                $("#ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#rua").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#uf").val(dados.uf);
                        $("#ibge").val(dados.ibge);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
        $("#numero").focus();
    });

});