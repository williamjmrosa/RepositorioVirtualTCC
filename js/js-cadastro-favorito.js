function adicionarFavorito(btn,idTCC){
    //Prevenir o comportamento padrao
    //event.preventDefault();

    //Selecionar o elemento HTML
    var select = $(btn);

    // Enviar uma requisição AJAX para obter os dados do nome via POST
    $.ajax({
        // URL da requisição
        url: '../controle/favorito-controle.php?OP=1',  
        // Metodo da requisição
        type: "POST",
        // Dados da requisição
        data: {
            idTCC: idTCC
        },
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        if(response == "TCC adicionado aos favoritos"){
            select.find("i").removeClass("bi bi-star").addClass("bi bi-star-fill");
            select.attr("onclick","removerFavorito(this,"+idTCC+")");
        }
        alert(response);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Acessar informações sobre o erro
        console.log("Erro na requisição AJAX:");
        console.log("Status: " + textStatus);
        console.log("Erro: " + errorThrown);

        // Verificar se o erro é um erro customizado
        if(jqXHR.responseText){
            console.log("Erro customizado: " + jqXHR.responseText);
        }
    });
}

function removerFavorito(btn,idTCC){
    //Selecionar o elemento HTML
    var select = $(btn);

    // Enviar uma requisição AJAX para obter os dados do nome via POST
    $.ajax({
        // URL da requisição
        url: '../controle/favorito-controle.php?OP=2',
        // Metodo da requisição
        type: "POST",
        // Dados da requisição
        data: {
            idTCC: idTCC
        },
        // Tipo da requisição
        dataType: 'json'
        // Quando a requisição for concluída com sucesso
    }).done(function (response) {
        if(response == "TCC removido dos favoritos"){
            select.find("i").removeClass("bi bi-star-fill").addClass("bi bi-star");
            select.attr("onclick","adicionarFavorito(this,"+idTCC+")");
        }
        alert(response);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Erro na requisição AJAX:");
        console.log("Status: " + textStatus);
        console.log("Erro: " + errorThrown);

        // Verificar se o erro é um erro customizado
        if(jqXHR.responseText){
            console.log("Erro customizado: " + jqXHR.responseText);
        }
    });
    
}
$(document).ready(function(){
    $("#btn-indicar").click(function(){
        var modal = $("#indicar");
        
        modal.find(".modal-body").append("Deseja indicar este TCC?");
        
    });

    
});