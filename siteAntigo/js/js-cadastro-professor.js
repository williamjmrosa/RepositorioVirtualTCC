// Carregar a lista de professores
$("#alterar").load("../alterarCadastros/alterarProfessor.php?OP=1",function(){
    $(this).find("tr td:nth-child(4)").mask('000.000.000-00');
    $(this).find("tr td:nth-child(5)").mask('99.999.999-9');
});
// Função para preencher o formulário com os dados do professor
function preencherForm(option, event) {
    // Prevenir o comportamento padrão do formulário
    event.preventDefault();
    // Selecionar o elemento HTML
    var select = $(option);
    // Obter o ID do elemento selecionado pelo atributo href
    var id = select.attr("href");

    // Enviar uma requisição AJAX para obter os dados do professor
    $.ajax({
        // URL da requisição
        url: '../alterarCadastros/alterarProfessor.php?OP=3&id=' + id,
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        // Obter os dados do professor se nenhum erro ocorrer
        if(response.error){
            // Mostrar o erro
            alert(response.error);
        }else{
            // Receber os dados do professor
            var professor = response;

            // Chamar a função para Preencher o formulário com os dados do professor
            completeForm(professor);

            // Alterar o texto do botão de envio
            $(".cadastro form button[type='submit']").text("Alterar Professor");

            // Alterar a ação do formulário
            $(".cadastro form").attr("action", "../controle/professor-controle.php?OP=2");

            // Remover o botão de limpar
            $("#limpar").remove();

            // Adicionar o botão de limpar
            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroProfessor.php" id="limpar" class="btn btn-danger"> Limpar </a>');

        }
        // Preencher o formulário com os dados retornados
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX: "+textStatus);
        // Verificar se o erro é um erro customizado
        if(jqXHR.responseText) {
            console.log("Erro customizado: " + jqXHR.responseText);
        }
    });

}

$(document).ready(function(){

    // Busca professores pelo tipo selecionado
    $("#buscarNome").on("input",function(){

        // Obter o texto digitado
        var nome = $(this).val();
        // Obter o tipo selecionado
        var tipo = $("select[name=busca] option:selected").val();
        // Enviar uma requisição AJAX para obter os dados
        $.post("../alterarCadastros/alterarProfessor.php?OP=2",{BUSCA: nome, TIPO: tipo},function(data){

            // Preencher a tabela
            $("#alterar").html(data);
            // Preencher os campos
            $("#alterar").find("tr td:nth-child(4)").mask('000.000.000-00');
            $("#alterar").find("tr td:nth-child(5)").mask('99.999.999-9');

        });

    });

});