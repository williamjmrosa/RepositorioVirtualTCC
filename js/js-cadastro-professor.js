$("#alterar").load("../alterarCadastros/alterarProfessor.php?OP=1",function(){
    $(this).find("tr td:nth-child(4)").mask('000.000.000-00');
    $(this).find("tr td:nth-child(5)").mask('99.999.999-9');
});

function preencherForm(option, event) {
    event.preventDefault();
    var select = $(option);
    var id = select.attr("href");

    $.ajax({
        url: '../alterarCadastros/alterarProfessor.php?OP=3&id=' + id,
        dataType: 'json'
    }).done(function (response) {
        if(response.error){
            alert(response.error);
        }else{
            var professor = response;

            completeForm(professor);

            $(".cadastro form button[type='submit']").text("Alterar Professor");

            $(".cadastro form").attr("action", "../controle/professor-controle.php?OP=2");

            $("#limpar").remove();

            $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroProfessor.php" id="limpar" class="btn btn-danger"> Limpar </a>');

        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Erro na requisição AJAX: "+textStatus);

        if(jqXHR.responseText) {
            console.log("Erro customizado: " + jqXHR.responseText);
        }
    });

}

$(document).ready(function(){

    $("#buscarNome").on("input",function(){

        var nome = $(this).val();
        var tipo = $("select[name=busca] option:selected").val();
        
        $.post("../alterarCadastros/alterarProfessor.php?OP=2",{BUSCA: nome, TIPO: tipo},function(data){

            $("#alterar").html(data);
            $("#alterar").find("tr td:nth-child(4)").mask('000.000.000-00');
            $("#alterar").find("tr td:nth-child(5)").mask('99.999.999-9');

        });

    });

});