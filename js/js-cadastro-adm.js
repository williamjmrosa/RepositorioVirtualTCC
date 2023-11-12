// Carregar a tabela de administradores
$("#alterar").load("../alterarCadastros/alterarADM.php?OP=1");

// Função para preencher o formulário com os dados do administrador
function preencherForm(option, event) {
    event.preventDefault();
    var select = $(option);
    var id = select.attr("href");

    $.ajax({
        url: '../alterarCadastros/alterarADM.php?OP=3&id=' + id,
        dataType: 'json'
    }).done(function (response) {
        // Obter os dados do administrador
        var administrador = response;
        //
        if(response.error){
            var insertHtml = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+response.error+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";

            $(".cadastro form").prepend(insertHtml);
        }else{

            // Receber os dados do administrador no formulário
            $("#nome").val(administrador.nome);

            $("#email").val(administrador.email);

            $("#limpar").remove();

            $(".cadastro form").attr("action", "../controle/adm-controle.php?OP=2");

            $(".cadastro form button[type='submit']").text("Alterar administrador");

            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroADM.php" id="limpar" class="btn btn-danger"> Limpar </a>');

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

    })

}

$(document).ready(function () {
   
    $("#buscarNome").on("input", function () {

        var busca = $(this).val();
        
        var tipo = $("select[name='busca'] option:selected").val();
        console.log(tipo);
        $.post("../alterarCadastros/alterarADM.php?OP=2", {BUSCA: busca, TIPO: tipo}, function (data) {
            $("#alterar").html(data);
        });
    });

});