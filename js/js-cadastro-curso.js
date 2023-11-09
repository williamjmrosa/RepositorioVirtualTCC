$("#alterar").load("../alterarCadastros/alterarCurso.php?OP=1");

function preencherForm(option, event) {
    event.preventDefault();

    var select = $(option);

    var id = select.attr("href");

    $.ajax({
        url: '../alterarCadastros/alterarCurso.php?OP=3&id=' + id,
        dataType: 'json'
    }).done(function (response) {
        var curso = response;

        if (response.error) {
            console.log(response.error);
        }else{
            $("#nome").val(curso.nome);

            $("input[name=ensino][value=" + curso.ensino + "]").prop("checked", true).change();

            $(".cadastro form button[type='submit']").text("Alterar Curso");

            $("#limpar").remove();

            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroCurso.php" id="limpar" class="btn btn-danger"> Limpar </a>');

            $(".cadastro form").attr("action", "../controle/curso-controle.php?OP=2");

            $(".cadastro form input[name=idCurso]").remove();

            $(".cadastro form").append('<input type="hidden" name="idCurso" value="' + curso.idCurso + '">');

            $(".cadastro form").show();
        }

    }).fail(function (jqXHR, textStatus, errorThrown) {
       
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX: " + textStatus);
        // Verificar se o erro é um erro customizado
        console.log("Erro customizado: " + jqXHR.responseText);


    });
}

$(document).ready(function () {
    
});