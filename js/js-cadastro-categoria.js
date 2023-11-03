var i = 0;

$("#alterar").load("../alterarCadastros/alterarCategoria.php?OP=1");

function adicionarNomeAltenativo(btn) {
    var raiz = $(btn).parent();
    $(raiz).append('<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="removerNomeAlternativo(this)"> - </a>');
    btn.remove();
    var insertHtml = '<div class="col-12">'
        + '<label for="nomeAlternativo' + i + '" class="form-label me-2">Nome Alternativo</label>'
        + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + i + '" name="nomeAlternativo[]">'
        + '<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="adicionarNomeAltenativo(this)"> + </a>'
        + '</div>';

    $("#nomesAlternativos").append(insertHtml);
    $("#nomeAlternativo" + i).focus();
    i++;
}

function removerNomeAlternativo(btn) {
    $(btn).parent().remove();
}

function preencherForm(option, event) {
    event.preventDefault();
    var select = $(option);

    var id = select.attr("href");

    var parametros = $.param({
        OP: 3,
        id: id
    });

    console.log(id);



    $.ajax({
        url: '../alterarCadastros/alterarCategoria.php?' + parametros,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            // Manipular o objeto JSON retornado
            var categoria = response[0];
            console.log(categoria.nomeAlternativo);
            // Exibir os valores das propriedades do objeto
            $('#nomeCategoria').val(categoria.nome);

            if(categoria.nomeAlternativo.length > 0){
                var nomeAlternativo = categoria.nomeAlternativo;
                for(var i = 0; i <nomeAlternativo.length; i++){
                    $("#nomesAlternativos").append('<div class="col-12">'
                        + '<label for="nomeAlternativo' + i + '" class="form-label me-2">Nome Alternativo</label>'
                        + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + i + '" name="nomeAlternativo[]" value="' + nomeAlternativo[i].nomeAlternativo + '">'
                        + '<a href="#" class="btn btn-danger"> Excluir </a>'
                        + '</div>');
                }
                
            }

            if (categoria.eSub == 1) {
                $("input[name=eSub][value='true']").prop("checked", true).change();

                $("#principal").val(categoria.categoriaPrincipal);

            }else{
                $("input[name=eSub][value='false']").prop("checked", true).change();
            }

            $('#cadastrarCategoria').show();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Acessar informações sobre o erro
            console.log("Erro na requisição AJAX:");
            console.log("Status: " + textStatus);
            console.log("Erro: " + errorThrown);

            // Verificar se o erro é um erro customizado
            if (jqXHR.responseText) {
                console.log("Erro customizado: " + jqXHR.responseText);
            }
        }
    });
}

$(document).ready(function () {

    $("input[name=eSub]").change(function () {
        if ($(this).val() == "false") {
            $("#catPrincipal").addClass("d-none");
        } else {
            $("#catPrincipal").removeClass("d-none");
        }
    });

    $("#buscarNome").on("input", function () {
        var select = $(this);
        var texto = select.val();

        var parametros = $.param({
            OP: 2,
            BUSCA: texto
        });

        console.log(parametros);

        $("#alterar").load("../alterarCadastros/alterarCategoria.php?" + parametros);

    });



});