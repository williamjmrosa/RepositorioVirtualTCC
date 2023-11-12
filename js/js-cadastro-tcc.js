//Menu Login Universal
$("#login").load("../menuLogin/menuLogin.php");

// Variavel de seleção de categoria principal
var categoriaSelecionada = null;

// Função para carregar o curso do aluno
function campusSelecionado(option){
    var select = $(option);
    var valor = select.val();
    $('#curso').load('../visao/selecteCurso.php?ID='+valor);

}

// Função para criar a lista de orientador do TCC
function listaOrientador(option){
    var select = $(option);
    var texto = select.text();
    var valor = select.val();
    if(impedirDuplicarOrientador(valor)){
    var opcao = '<option value="'+valor+'" selected onclick="removerOrientador(this)">'+texto+'</otion>';

    $('#orientador').append(opcao);
    }
}


// Função que impede a entrada de orientador dublicado
function impedirDuplicarOrientador(valor){
    if($('#orientador option[value="'+valor+'"]').length){
       return false;
    }else{
        return true;
    }
}

// Função para remover um orientador da lista de orientadores selecionados
function removerOrientador(option){
    var select = $(option);
    select.remove();

}

// Função para remover uma categoria marcada
function removerCategoria(input){
  $(input).parent().remove();
}

// Função para limpar a definição da lista de SubCategorias
function limpar(){
  categoriaSelecionada = null;

  $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php');

}

$(document).ready(function(){
    
    // Acompanhar as alterações no campo de texto usando o evento input
    $('#buscaAluno').on('input', function() {
      // Obter o valor do texto digitado no campo
      var textoDigitado = $(this).val();

      textoDigitado = $.param({BUSCA:textoDigitado});
    
    // Buscar os alunos que correspondem ao texto digitado
    $('#autor').load('../visao/selectAluno.php?'+textoDigitado);

    });

    $('#buscaOrientador').on('input', function() {
      
      var textoDigitado = $(this).val();

      textoDigitado = $.param({BUSCA:textoDigitado});
      
      $('#listaOrientador').load('../visao/selectProfessor.php?'+textoDigitado);

      console.log(textoDigitado);
    });

    // Busca categorias principais que correspondem ao texto digitado
    $('#buscaCategoriaPrincipal').on('input', function() {

      var textoDigitado = $(this).val();

      textoDigitado = $.param({BUSCA:textoDigitado});

      $('#categoriaPrincipal').load('../visao/selectCategoriaPrincipal.php?'+textoDigitado);

      console.log(textoDigitado);

    });

    // Busca categorias secundarias que correspondem ao texto digitado
    $('#buscaCategoriaSecundaria').on('input', function() {

      var textoDigitado = $(this).val();

      if(categoriaSelecionada == null){
        textoDigitado = $.param({BUSCA:textoDigitado});
      }else{
        let IDS = [categoriaSelecionada];
        
        var select = $('#categoriaSecundaria');
        select.find('option').each(function(){
          var value = $(this).val();

          IDS.push(value);

        });

        console.log('ID: '+IDS);

        var IDString = IDS.join(',');

        textoDigitado = $.param({ID:IDString,BUSCA:textoDigitado});

        console.log(textoDigitado);
      }

      $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);

    });

    // Seleciona a categoria principal e carrega as categorias secundarias
    $('#categoriaPrincipal').change(function(){
      var opcaoSelecionada = $(this).val();
      var texto = $(this).find('option:selected').text();
      
      categoriaSelecionada = opcaoSelecionada;

      var textoDigitado = $.param({ID:opcaoSelecionada,BUSCA:''});

      $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);

      if(!$('#categoriasSalvas input[value="'+opcaoSelecionada+'"]').length && opcaoSelecionada > 0){
        $('#categoriasSalvas').append('<div class="d-inline-block m-2"><input class="form-check-input" type="checkbox" name="categorias[]" value="'+opcaoSelecionada+'" checked onclick="removerCategoria(this)"> <label class="form-check-label">'+texto+'</label></div>');
      }

    });

    // Seleciona a categoria secundaria e carrega as categorias subsecundarias
    $('#categoriaSecundaria').change(function(){

      var opcaoSelecionada = $(this).val();
      var texto = $(this).find('option:selected').text();
      
      if(!$('#categoriasSalvas input[value="'+opcaoSelecionada+'"]').length && opcaoSelecionada > 0){

        $('#categoriasSalvas').append('<div class="d-inline-block m-2"><input class="form-check-input" type="checkbox" name="categorias[]" value="'+opcaoSelecionada+'" checked onclick="removerCategoria(this)"> <label class="form-check-label">'+texto+'</label></div>');
      
        var textoDigitado = $.param({ID:opcaoSelecionada,BUSCA:''});

        $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);
      
      }

    });


    // Busca o aluno correspondente a matricula e carrega o campus e o curso desse aluno
    $('#autor').click(function(){
      var autor = $('#autor option:selected').val();

      $('#divCampus').load('../visao/inputCampus.php?BUSCA='+autor);
      $('#divCurso').load('../visao/inputCurso.php?BUSCA='+autor);

    });
    // Selecionar todas as opções do select múltiplo do orientador
    $('#orientador').click(function(){

      // Selecionar todas as opções do select múltiplo
      $('#orientador option').prop('selected', true);

    });

});