// Carregar lista de visitantes
$("#alterar").load('../alterarCadastros/alterarVisitante.php?OP=1');

// Função para preencher o formulário com os dados do visitante
function preencherForm(option, event) {
    event.preventDefault();
    var select = $(option);
    var id = select.attr("href");

    $.ajax({
        url: '../alterarCadastros/alterarVisitante.php?OP=3&id=' + id,
        dataType: 'json'
    }).done(function (response) {
        // Obter os dados do visitante
        var visitante = response;

        // ver se houve erro
        if(response.error){
           var insertHtml = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+response.error+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";

           $(".cadastro form").prepend(insertHtml);
        }else{
            // Receber os dados do visitante no formulário
            $("#nome").val(visitante.nome);

            $("#email").val(visitante.email);

            $("#limpar").remove();

            $(".cadastro form").attr("action", "../controle/visitante-controle.php?OP=2");

            $(".cadastro form button[type='submit']").text("Alterar Visitante");

            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroVisitante.php" id="limpar" class="btn btn-danger"> Limpar </a>');

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
   
    // Buscar pelo tipo selecionado o texto digitado
    $("#buscarNome").on("input", function () {
        // Obter o texto digitado
        var busca = $(this).val();
        // Obter o tipo selecionado
        var tipo = $("select[name=busca] option:selected").val();
        // Carregar lista de visitantes passando o tipo e o texto digitado como parâmetro para a requisição
        $.post("../alterarCadastros/alterarVisitante.php?OP=2",{BUSCA: busca, TIPO: tipo},function(data){
            $("#alterar").html(data);
        });
    
    });

});