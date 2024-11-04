// atributo para dar idUnico ao input do nome da alterativo
var i = 0;

// Carregar a lista de Categorias
$("#alterar").load("../alterarCadastros/alterarCategoria.php?OP=1");
$("#login").load("../menuLogin/menuLogin.php");

// Função para adicionar nome alternativo
function adicionarNomeAltenativo(btn) {

    // Selecionar o elemento HTML e adicionar o botão
    var raiz = $(btn).parent();
    $(raiz).append('<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="removerNomeAlternativo(this)"> - </a>');
    // Remover o botão que disparou esta função
    btn.remove();
    // Adicionar o input de nome alternativo
    var insertHtml = '<div class="col-12">'
        + '<label for="nomeAlternativo' + i + '" class="form-label me-2">Nome Alternativo</label>'
        + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + i + '" name="nomeAlternativo[]">'
        + '<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="adicionarNomeAltenativo(this)"> + </a>'
        + '</div>';

    // Adicionar o input ao html
    $("#nomesAlternativos").append(insertHtml);
    // Focar o input gerado
    $("#nomeAlternativo" + i).focus();
    //incrementa o idunico do input
    i++;
}

// Função para alterar nome
function alterarNome(event,btn){
    //Prevenir o comportamento padrao
    event.preventDefault();

    //Selecionar o elemento HTML
    var select = $(btn);

    //Obter o ID do elemento selecionado pelo atributo href
    var id = select.attr("href");

   //Obter o nome do elemento selecionado pelo atributo href
    var texto = select.parent().find("#nomeAlternativo"+id).val();

     // Enviar uma requisição AJAX para obter os dados do nome via POST
    $.ajax({
        // URL da requisição
        url: '../controle/categoria-controle.php?OP=3',
        // Metodo da requisição
        type: "POST",
        // Dados da requisição
        data: {
            id: id,
            nomeAlternativo: texto
        },
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        // Manipular o objeto JSON retornado
        if(response.msg){
            //Gera alerta de sucesso de atualização do nome
            var alerta = "<div class='alert alert-success alert-dismissible fade show' role='alert'>"+response.msg+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }else{
            //Gera alerta de erro de atualização do nome
            var texto = "Erro: ";
            response.forEach(function(erro){
                texto += "<p>" + erro + "</p>";
            });
            //Gera alerta de erro de atualização do nome
            var alerta = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+texto+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
        //Add o alerta ao inicio do form
        $("#cadastrarCategoria").prepend(alerta);

        //Mostra erro de atualização do nome e ajax
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

// Função para remover nomeAlternativo
function removerNomeAlternativo(btn) {
    $(btn).parent().remove();
}

// Função para preencher o formulário com os dados da categoria
function preencherForm(option, event) {
    // Prevenir o comportamento padrão do formulário
    event.preventDefault();
    // Selecionar o elemento HTML
    var select = $(option);
    // Obter o ID do elemento selecionado pelo atributo href
    var id = select.attr("href");

    // Gerar os parâmetros da requisição AJAX
    var parametros = $.param({
        OP: 3,
        id: id
    });

    // Enviar uma requisição AJAX para obter os dados da categoria
    $.ajax({
        // URL da requisição
        url: '../alterarCadastros/alterarCategoria.php?' + parametros,
        // Tipo da requisição
        method: 'GET',
        // Dados da requisição
        dataType: 'json',
        // Quando a requisição for concluída com sucesso
        success: function (response) {
            // Manipular o objeto JSON retornado
            var categoria = response;
            // Exibir os valores das propriedades do objeto
            $('#nomeCategoria').val(categoria.nome);
            if(response.error){
                alert(response.error);
            }else{ 
                if(categoria.nomeAlternativo.length > 0){
                    var nomeAlternativo = categoria.nomeAlternativo;
                    var divNomeAlternativo = $(".alternativas");
                    divNomeAlternativo.empty();
                    for(var i = 0; i <nomeAlternativo.length; i++){
                        var valor = nomeAlternativo[i];
                        divNomeAlternativo.append('<div class="col-12">'
                            + '<label for="nomeAlternativo' + valor.idNomeAlternativo + '" class="form-label me-2">Nome Alternativo</label>'
                            + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + valor.idNomeAlternativo + '" value="' + valor.nomeAlternativo + '">'
                            + '<a href="../controle/categoria-controle.php?OP=5&id=' + valor.idNomeAlternativo + '" class="btn btn-danger ms-1"> Excluir </a>'
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
            //Alterar o botão de cadastrar
            $("#cadastrarCategoria button[type='submit']").text("Alterar Categoria");
            
            //Remover botão de limpar
            $("#limpar").remove();

            //Adicionar botão de limpar
            $("#cadastrarCategoria button[type='submit']").parent().append('<a href="../visao/telaCadastroCategoria.php" id="limpar" class="btn btn-danger"> Limpar </a>');

            //Alterar o action do form
            $("#cadastrarCategoria").attr("action", "../controle/categoria-controle.php?OP=2");

            //Remover o id da categoria
            $("#cadastrarCategoria input[name=id]").remove();

            //Adicionar o id da categoria
            $("#cadastrarCategoria").append('<input type="hidden" name="id" value="' + categoria.idCategoria + '">');

            //Mostrar o form
            $('#cadastrarCategoria').show();

            }
        },
        // Quando a requisição falhar
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

    // Mostrar a opção de categoria principal se o eSub for true
    $("input[name=eSub]").change(function () {
        if ($(this).val() == "false") {
            $("#catPrincipal").addClass("d-none");
        } else {
            $("#catPrincipal").removeClass("d-none");
        }
    });

    // Buscar categoria por nome digitado no input
    $("#buscarNome").on("input", function () {
        // Selecionar o elemento HTML
        var select = $(this);
        // Obter o texto digitado
        var texto = select.val();
        //Gerar o parâmetros
        var parametros = $.param({
            OP: 2,
            BUSCA: texto
        });

        // Carregar o novo conteúdo
        $("#alterar").load("../alterarCadastros/alterarCategoria.php?" + parametros);

    });

    // Buscar categoria por nome digitado no input
    $('#searchInputCategoria').on('input', function() {
        // Obter o texto digitado
        var searchText = $(this).val().toLowerCase();
        // Filtrar as opções de categoria que correspondem aos termos de busca
        $('#principal option').each(function() {
          var optionText = $(this).text().toLowerCase();
          $(this).toggle(optionText.indexOf(searchText) > -1);
        });
      });
        

});