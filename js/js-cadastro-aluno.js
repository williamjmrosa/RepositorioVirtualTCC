// Função carregar a lista de alunos
$("#alterar").load("../alterarCadastros/alterarAluno.php?OP=1",function(){
    // Carregar a mascara de cpf e rg
    $(this).find("tr td:nth-child(4)").mask('000.000.000-00');
    $(this).find("tr td:nth-child(5)").mask('99.999.999-9');
});

// Função para preencher o formulário com os dados do aluno
function preencherForm(option, event) {
    // Prevenir o comportamento padrão do formulário
    event.preventDefault();
    // Selecionar o elemento HTML 
    var select = $(option);
    // Obter o ID do elemento selecionado pelo atributo href
    var id = select.attr("href");

    // Enviar uma requisição AJAX para obter os dados do aluno
    $.ajax({
        // URL da requisição
        url: '../alterarCadastros/alterarAluno.php?OP=3&id=' + id,
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        // Obter os dados do aluno se nenhum erro ocorrer
        if(response.error){
            // Mostrar o erro
            alert(response.error);
        }else{
            // Receber os dados do aluno
            var aluno = response;

            // Chamar a função para Preencher o formulário com os dados do aluno
            completeForm(aluno);

            // Preecher o formulário com os dados retornados
            $("#campus").val(aluno.campus);
            
            campusSelecionado($("#campus option:selected")[0],function(){
                $("#curso").val(aluno.curso);    
            });

            // Mudar o texto do botão cadastro
            $(".cadastro form button[type='submit']").text("Alterar Aluno");

            // Mudar a action do formulário
            $(".cadastro form").attr("action", "../controle/aluno-controle.php?OP=2");

            // Remover o botão limpar
            $("#limpar").remove();
            
            // Adicionar o botão limpar
            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroAluno.php" id="limpar" class="btn btn-danger"> Limpar </a>');
            
        }
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
    
    // Busca aluno pelo parametro selecionado no select e atualiza a tabela
    $("#buscarNome").on("input",function(){

        // Obter o nome digitado
        var nome = $(this).val();
        // Obter o tipo selecionado
        var tipo = $("select[name=busca] option:selected").val();
        // Enviar uma post para obter os dados
        $.post("../alterarCadastros/alterarAluno.php?OP=2",{BUSCA: nome, TIPO: tipo},function(data){
            //console.log(data);
            // Preencher a tabela com os dados retornados
            $("#alterar").html(data);
            // Carregar a mascara de cpf e rg
            $("#alterar").find("tr td:nth-child(4)").mask('000.000.000-00');
            $("#alterar").find("tr td:nth-child(5)").mask('99.999.999-9');
        })

    });

});