categoriaSelecionada = null;

$("#listaCampus").load("../filtrosIndex/checkCampus.php");

$("#listaCursos").load("../filtrosIndex/checkCursos.php");

$("#listarCategoriasPrincipal").load("../filtrosIndex/checkCategorias.php?OP=1");

$("#listaSubCategorias").load("../filtrosIndex/checkCategorias.php?OP=2");

$(document).ready(function(){
    $("#buscarCampus").on("input",function(){

        $("#listaCampus").prop("open",true);

        var textoDigitado = $(this).val();

        textoDigitado = $.param({
            BUSCA: textoDigitado
        });
        
        $("#listaCampus").load("../filtrosIndex/checkCampus.php?"+textoDigitado);
    });

    $("#buscarCurso").on("input",function(){

        $("#listaCursos").prop("open",true);

        var textoDigitado = $(this).val();

        textoDigitado = $.param({
            BUSCA: textoDigitado
        });

        $("#listaCursos").load("../filtrosIndex/checkCursos.php?"+textoDigitado);

    });

    $("#listarCampus label input[name='listarCampus[]']").click(function(){
       
        alert('teste');

        

    });
});