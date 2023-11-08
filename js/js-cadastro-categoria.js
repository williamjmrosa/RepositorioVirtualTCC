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

function alterarNome(event,btn){
    event.preventDefault();

    var select = $(btn);

    var id = select.attr("href");

    var texto = select.parent().find("#nomeAlternativo"+id).val();

    $parametros = $.param({
        OP: 2,
        id: id,
        nome: texto
    });

    $.ajax({
        url: '../controle/categoria-controle.php?OP=3',
        type: "POST",
        data: {
            id: id,
            nomeAlternativo: texto
        },
        dataType: 'json'

    }).done(function (response) {
        if(response.msg){
            var alerta = "<div class='alert alert-success alert-dismissible fade show' role='alert'>"+response.msg+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }else{
            var texto = "<p>Erros: </p>";
            response.forEach(function(erro){
                texto += "<p>" + erro + "</p>";
            });
            var alerta = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+texto+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
        $("#cadastrarCategoria").prepend(alerta);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX:");
        console.log("Status: " + textStatus);
        console.log("Erro: " + errorThrown);

        // Verificar se o erro é um erro customizado
        if (jqXHR.responseText) {
            console.log("Erro customizado: " + jqXHR.responseText);
        }
    });
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
            var categoria = response;
            console.log(categoria.nomeAlternativo);
            // Exibir os valores das propriedades do objeto
            $('#nomeCategoria').val(categoria.nome);
            if(response.error){
                alert(response.error);
            }else if(categoria.nomeAlternativo.length > 0){
                var nomeAlternativo = categoria.nomeAlternativo;
                var divNomeAlternativo = $(".alternativas");
                divNomeAlternativo.empty();
                for(var i = 0; i <nomeAlternativo.length; i++){
                    var valor = nomeAlternativo[i];
                    divNomeAlternativo.append('<div class="col-12">'
                        + '<label for="nomeAlternativo' + valor.idNomeAlternativo + '" class="form-label me-2">Nome Alternativo</label>'
                        + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + valor.idNomeAlternativo + '" value="' + valor.nomeAlternativo + '">'
                        + '<a href="../controle/categoria-controle.php?OP=4&id=' + valor.idNomeAlternativo + '" class="btn btn-danger ms-1"> Excluir </a>'
                        + '<a href="' + valor.idNomeAlternativo + '" class="btn btn-primary ms-1" onclick="alterarNome(event,this)"> Alterar </a>'
                        + '</div>');
                }
                
            }

            if (categoria.eSub == 1) {
                $("input[name=eSub][value='true']").prop("checked", true).change();

                $("#principal").val(categoria.categoriaPrincipal);

            }else{
                $("input[name=eSub][value='false']").prop("checked", true).change();
            }

            $("#cadastrarCategoria button[type='submit']").text("Alterar Categoria");
            
            $("#limpar").remove();

            $("#cadastrarCategoria button[type='submit']").parent().append('<a href="../visao/telaCadastroCategoria.php" id="limpar" class="btn btn-danger"> Limpar </a>');

            $("#cadastrarCategoria").attr("action", "../controle/categoria-controle.php?OP=2");

            $("#cadastrarCategoria input[name=id]").remove();

            $("#cadastrarCategoria").append('<input type="hidden" name="id" value="' + categoria.idCategoria + '">');

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