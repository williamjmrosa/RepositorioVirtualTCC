// Carregar a tabela de bibliotecários
$("#alterar").load("../alterarCadastros/alterarBibliotecario.php?OP=1");

// Função para preencher o formulário com os dados do bibliotecário
function preencherForm(option, event) {
    event.preventDefault();
    var select = $(option);
    var id = select.attr("href");

    $.ajax({
        url: '../alterarCadastros/alterarBibliotecario.php?OP=3&id=' + id,
        dataType: 'json'
    }).done(function (response) {
        // Obter os dados do bibliotecario
        var bibliotecario = response;

        // ver se houve erro
        if(response.error){
           var insertHtml = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+response.error+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";

           $(".cadastro form").prepend(insertHtml);
        }else{
            // Receber os dados do bibliotecario no formulário
            $("#nome").val(bibliotecario.nome);

            $("#email").val(bibliotecario.email);

            $("#limpar").remove();

            $(".cadastro form").attr("action", "../controle/bibliotecario-controle.php?OP=2");

            $(".cadastro form button[type='submit']").text("Alterar bibliotecario");

            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroBibliotecario.php" id="limpar" class="btn btn-danger"> Limpar </a>');

            $(".cadastro form input[name='id']").remove();

            $(".cadastro form").append('<input type="hidden" name="id" value="'+id+'">');

        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX: "+textStatus);

        // Verificar se o erro é um erro customizado
        if(jqXHR.responseText) {
            console.log("Erro customizado: "+jqXHR.responseText);
        }
        
    });

}

$(document).ready(function () {
   
    // Buscar pelo tipo selecionado e o texto digitado
    $("#buscarNome").on("input", function () {

        var busca = $(this).val();

        var tipo = $("select[name=busca] option:selected").val();

        $.post("../alterarCadastros/alterarBibliotecario.php?OP=2", {BUSCA: busca, TIPO: tipo}, function (data) {
            $("#alterar").html(data);
        });

    });

});