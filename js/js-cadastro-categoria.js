var i = 0;

function adicionarNomeAltenativo(btn) {
    var raiz = $(btn).parent();
    $(raiz).append('<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="removerNomeAlternativo(this)"> - </a>');
    btn.remove();
    var insertHtml = '<div class="col-12">'
        + '<label for="nomeAlternativo' + i + '" class="form-label">Nome Alternativo</label>'
        + '<input type="text" class="form-control d-inline w-50" id="nomeAlternativo' + i + '" name="nomeAlternativo[]">'
        + '<a href="#" class="btn btn-primary ms-3 ps-3 pe-3" onclick="adicionarNomeAltenativo(this)"> + </a>'
        + '</div>';
    i++;

    $("#nomesAlternativos").append(insertHtml);
}

function removerNomeAlternativo(btn) {
    $(btn).parent().remove();
}

$(document).ready(function () {

});