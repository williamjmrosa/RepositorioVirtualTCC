// Carregar a lista de cursos
$("#alterar").load("../alterarCadastros/alterarCurso.php?OP=1");
// Carregar o menu de login
$("#login").load("../menuLogin/menuLogin.php");

// Função para preencher o formulário com os dados do curso
function preencherForm(option, event) {
    // Prevenir o comportamento padrão do formulário
    event.preventDefault();

    // Selecionar o elemento HTML
    var select = $(option);

    // Obter o ID do elemento selecionado pelo atributo href
    var id = select.attr("href");

    // Enviar uma requisição AJAX para obter os dados do curso
    $.ajax({
        // URL da requisição
        url: '../alterarCadastros/alterarCurso.php?OP=3&id=' + id,
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        // Obter os dados do curso
        var curso = response;

        // Preencher o formulário com os dados do curso e montar o formulário se não existir erros
        if (response.error) {
            console.log(response.error);
        }else{
            // Preencher o formulário com os dados do curso
            $("#nome").val(curso.nome);

            $("input[name=ensino][value=" + curso.ensino + "]").prop("checked", true).change();

            $(".cadastro form button[type='submit']").text("Alterar Curso");

            // Remover o botão de limpar
            $("#limpar").remove();

            // Adicionar o botão de limpar
            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroCurso.php" id="limpar" class="btn btn-danger"> Limpar </a>');

            // Mudando o action do formulário
            $(".cadastro form").attr("action", "../controle/curso-controle.php?OP=2");

            // Remover o campo idCurso
            $(".cadastro form input[name=idCurso]").remove();

            // Adicionar o campo id
            $(".cadastro form").append('<input type="hidden" name="idCurso" value="' + curso.idCurso + '">');
            
            // Mostrar o formulário
            $(".cadastro form").show();
        }
        // Mostrar erros da pagina acessada se existirem e do proprio ajax
    }).fail(function (jqXHR, textStatus, errorThrown) {
       
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX: " + textStatus);
        // Verificar se o erro é um erro customizado
        console.log("Erro customizado: " + jqXHR.responseText);


    });
}

$(document).ready(function () {
    
    // Função para buscar curso por nome
    $("#buscarNome").on("input", function () {
        // Obter o valor do campo de busca
        var nome = $(this).val();
        
        // Montar o parâmetro de busca
        var parametros = $.param({
            OP: 2,
            BUSCA: nome
        });

        // Carrega a lista de cursos como  uma tabela
        $("#alterar").load("../alterarCadastros/alterarCurso.php?"+parametros);

    });
});