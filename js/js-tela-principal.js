var campusSelecionado = null;

// Função para carregar a lista de Campus
$("#listaCampus").load("../filtrosIndex/checkCampus.php",function(){
    var valor = $('#formFiltrar input[name="campus"]');
    var buscar = $(this).find('input[name="listarCampus[]"][value="' + valor.val() + '"]');

    buscar.prop('checked', true);

    valor.parent().remove();

    campusSelecionados(buscar);

});

// Função para carregar a lista de Cursos
$("#listaCursos").load("../filtrosIndex/checkCursos.php?OP=1");

// Função para carregar a lista de Categorias
$("#listarCategoriasPrincipal").load("../filtrosIndex/checkCategorias.php?OP=1",function(){
    var categorias = $('#formFiltrar input[name="categorias[]"]');
    var buscar = $(this)
    categorias.each(function(){
        var valor = $(this);
        var cat = buscar.find('input[value="' + valor.val() + '"]');

        cat.prop('checked', true);
        
        valor.parent().remove();

        categoriaPrincipalSelecionados(cat);
    });
});

// Função para carregar a lista de SubCategorias
$("#listaSubCategorias").load("../filtrosIndex/checkCategorias.php?OP=2", function () {
    var summary = $(this).find('summary');

    summary.text("Todas as Sub Categorias");
});

// Função que add a categoria principal selecionada ao filtro e carregar a lista de SubCategorias dessa categoria principal
function categoriaPrincipalSelecionados(option) {
    var select = $(option)

    var valor = select.val();

    if (select.prop('checked')) {
        // O checkbox está marcado
        console.log('O checkbox está marcado');

        $('.categoria[value="' + valor + '"]').prop('checked', true);

        textoDigitado = $.param({
            OP: 2,
            ID: valor
        });

        var filtroSelecionado = "<span class='badge bg-info me-1'>" + select.parent().text() + "<input type='hidden' name='categorias[]' value='" + valor + "'><input class='btn-close' type='button' onclick='removerCategoria(this)'></span>";

        $("#filtrosSelecionados form").append(filtroSelecionado);

        var listaSubCategorias = "<div id='" + valor + "'><input type='text' id='" + valor + "Buscar' class='form-control mb-2 buscarCategoriaSecundaria' placeholder='Buscar sub-categoria' oninput='buscarCategoriaSecundaria(this)'/><details></details><hr></div>";

        $(".filtro-direito").append(listaSubCategorias);

        var ondeCarregar = $("#" + valor + "").find("details");
        ondeCarregar.load("../filtrosIndex/checkCategorias.php?" + textoDigitado, function () {
            var details = $(this);
            var input = details.find('input').length == 0;
            if (input) {
                $(this).parent().remove();
            }else{

                $("#listaSubCategorias input[type='checkbox']").each(function () {
                    var seleciona = $(this);
                    
                    if(seleciona.prop("checked")){
                        alert("seleciona revisado: " + seleciona.val());
                        details.find('input[type="checkbox"][value="' + seleciona.val() + '"]').prop('checked', true);
                    }
                    
                });
            }

        });

        ondeCarregar.prop("open", true);

    } else {
        $("#" + valor).remove();
        $('.categoria[value="' + valor + '"]').prop('checked', false);
        $('#filtrosSelecionados input[name="categorias[]"][value="' + valor + '"]').parent().remove();
        // O checkbox não está marcado
        console.log('O checkbox não estava marcado');

    }

    if(option instanceof HTMLElement){
        $('#formFiltrar').submit();
    }

}

// Função que remove a categoria 
function removerCategoria(button) {
    var select = $(button);

    var valor = select.parent().find("input[type='hidden']").val();

    $(".categoria[type='checkbox'][value='" + valor + "']").prop("checked", false);

    select.parent().remove();

    $("#" + valor).remove();

    $('#formFiltrar').submit();
}

// Busca sub-categorias
function buscarCategoriaSecundaria(input) {
    var select = $(input);

    console.log(select.val());

    select.parent().find("details").prop("open", true);

    var id = select.parent().attr("id");

    if (select.val() != "" && id != null) {
        var textoDigitado = $.param({
            OP: 4,
            ID: id,
            BUSCA: select.val()
        });
    } else if (select.val() != "") {
        var textoDigitado = $.param({
            OP: 4,
            BUSCA: select.val()
        });
    } else if(select.val() == "" && id != null) {
        var textoDigitado = $.param({
            OP: 2,
            ID: id
        });

    } else {
        var textoDigitado = $.param({
            OP: 2
        });
    }

    // Passa o parâmetro de busca e carrega a lista de sub-categorias
    select.parent().find("details").load("../filtrosIndex/checkCategorias.php?" + textoDigitado, function () {
        if (id == null) {
            var summary = $(this).find('summary');

            summary.text("Todas as Sub Categorias");
        }
    });

}

// Função que add a campus selecionado ao filtro e carregar a lista de Cursos desse campus
function campusSelecionados(option) {
    var select = $(option);

    var valor = select.val();

    if ($(select).prop('checked')) {
        // O checkbox está marcado
        console.log('O checkbox está marcado');

        $('input[name="listarCampus[]"]').prop('checked', false);

        select.prop('checked', true);

        textoDigitado = $.param({
            OP: 1,
            ID: valor
        });

        var filtroSelecionado = "<span class='badge bg-danger me-1'>" + select.parent().text() + "<input type='hidden' name='campus' value='" + valor + "'><input class='btn-close' type='button' onclick='removerCampus(this)'></span>";

        $("#filtrosSelecionados form").append(filtroSelecionado);

        $("#filtrosSelecionados").find("input[value='" + campusSelecionado + "']").parent().remove();

        campusSelecionado = valor;

    } else {
        // O checkbox não está marcado
        console.log('O checkbox não está marcado');

        $('#filtrosSelecionados input[name="campus[]"][value="' + campusSelecionado + '"]').parent().remove();

        campusSelecionado = null;

        textoDigitado = $.param({
            OP: 1
        });

    }

    if(option instanceof HTMLElement){
        $('#formFiltrar').submit();
    }

    $('#listaCursos').prop("open", true);

    $('#listaCursos').load("../filtrosIndex/checkCursos.php?" + textoDigitado);
}

function removerCampus(button) {
    var select = $(button);

    var valor = select.parent().find("input[type='hidden']").val();

    $("#listaCampus input[value='" + valor + "']").prop("checked", false);

    campusSelecionado = null;

    select.parent().remove();

    $("#listaCursos").load("../filtrosIndex/checkCursos.php?OP=1");

    $('#formFiltrar').submit();
}

function removerSubCategoria(button) {
    var select = $(button);

    var valor = select.parent().find("input[type='hidden']").val();

    $("#listarCategoriasPrincipal input[value='" + valor + "']").prop("checked", false);

    select.parent().remove();

    $('#formFiltrar').submit();
}



$(document).ready(function () {

    // Função para buscar campus
    $("#buscarCampus").on("input", function () {

        $(".filtro-esquerdo details").prop("open", false);

        $("#listaCampus").prop("open", true);

        var textoDigitado = $(this).val();

        textoDigitado = $.param({
            BUSCA: textoDigitado
        });

        // Passa o parametro de busca e carrega a lista de campus
        $("#listaCampus").load("../filtrosIndex/checkCampus.php?" + textoDigitado);
    });

    $("#buscarCategoriaPrincipal").on("input", function () {

        $('.filtro-esquerdo details').prop("open", false);

        $("#listarCategoriasPrincipal").prop("open", true);

        var textoDigitado = $(this).val();

        if (textoDigitado != "") {
            textoDigitado = $.param({
                OP: 3,
                BUSCA: textoDigitado
            });

        } else {
            textoDigitado = $.param({
                OP: 3
            });
        }

        // Passa o parametro de buscar e carrega a lista de categorias principais
        $("#listarCategoriasPrincipal").load("../filtrosIndex/checkCategorias.php?" + textoDigitado);

    });

    // Função para buscar curso
    $("#buscarCurso").on("input", function () {

        $(".filtro-direito details").prop("open", false);

        $("#listaCursos").prop("open", true);

        var textoDigitado = $(this).val();

        console.log(textoDigitado);

        // IF para saber se esta buscando o curso de um campus ou todos os cursos
        if (campusSelecionado != null) {

            textoDigitado = $.param({
                OP: 2,
                ID: campusSelecionado,
                BUSCA: textoDigitado
            });

        } else {
            textoDigitado = $.param({
                OP: 2,
                BUSCA: textoDigitado
            });
        }

        // Passa o parametro de busca e carrega a lista de cursos
        $("#listaCursos").load("../filtrosIndex/checkCursos.php?" + textoDigitado);

    });

    $("#proximo").click(function (event) {
        event.preventDefault();
        var proximo = $("input[name='paginaAtual']");
        if(proximo.length > 0){
            proximo.val(parseInt(proximo.val()) + 1);
            $("#formFiltrar").submit();  
            
       }
    });

    $("#anterior").click(function (event) {
        event.preventDefault();
        var anterior = $("input[name='paginaAtual']");
        if(anterior.length > 0){
            //alert(parseInt(anterior.val()) - 1);
            anterior.val(parseInt(anterior.val()) - 1);
            $("#formFiltrar").submit();
        } 
    });

    $(".pagina").click(function (event) {
        event.preventDefault();
        var pagina = $('input[name="paginaAtual"]');
        if(pagina.length > 0){
            pagina.val($(this).text());
            $("#formFiltrar").submit();
        }
    });

});