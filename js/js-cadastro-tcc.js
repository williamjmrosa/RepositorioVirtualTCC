//Menu Login Universal
$("#login").load("../menuLogin/menuLogin.php");
// Carregar lista de tccs
$("#alterar").load("../alterarCadastros/alterarTCC.php?OP=1");

// Função para decodificar entidades HTML usando a biblioteca he.js
function decodeHtmlEntities(json) {
  try {
    var tcc = JSON.stringify(json);

    tcc = he.decode(tcc);

    tcc = JSON.parse(tcc);

    return tcc;
    
  } catch (error) {
    //console.error('Erro ao decodificar entidades HTML:', error);
    return json;
  }
}

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

// Função para preencher o formulário com os dados do TCC
function preencherForm(option,event){
  event.preventDefault();

  var select = $(option);

  var id = select.attr('href');

  $.ajax({
    url: '../alterarCadastros/alterarTCC.php?OP=3&id='+id,
    dataType: 'json'
  }).done(function(response){
    
    if(response.error){
      var insertHtml = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>"+response.error+"<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      $(".cadastro form").prepend(insertHtml);
    }else{

      var tcc = decodeHtmlEntities(response);
      //var tcc = JSON.parse(tccs); 
      // Receber os dados do TCC no formulário
      $("#titulo").val(tcc.titulo);
      $("#descricao").val(tcc.descricao);
      console.log(tcc.categorias);

      $("#autor").load("../visao/selectAluno.php",function(){
        $("#buscaAluno").val("");
        $("#autor").val(tcc.aluno.matricula).click();
      });
      $("#listaOrientador").load("../visao/selectProfessor.php",function(){
        $("#buscaOrientador").val("");
        var lista = $(this);
        $("#orientador").empty();
        tcc.orientador.forEach(matricula => {
          lista.val(matricula.matricula).trigger("click");
          listaOrientador($(lista).find("option:selected"));
        });
        
      });
      $("#categoriasSalvas div").remove();
      $("#categoriaSecundaria").load("../visao/selectCategoriaSecundaria.php",function(){
        var lista = $("#categoriaSecundaria");
        $("#buscaCategoriaSecundaria").val("");
        //lista.val(tcc.categorias[1].idCategoria).trigger("change");
        for(var i = 0; i < tcc.categorias.length; i++){
          lista.val(tcc.categorias[i].idCategoria).trigger("change");
        }
      });
      $("#categoriaPrincipal").load("../visao/selectCategoriaPrincipal.php",function(){
        var lista = $(this);
        $("#buscaCategoriaPrincipal").val("");
        for(var i = 0; i < tcc.categorias.length; i++){
          lista.val(tcc.categorias[i].idCategoria).trigger("change");
        }
      })
     
      $("#limpar").remove();
      $(".cadastro form").attr("action", "../controle/tcc-controle.php?OP=2");
      $(".cadastro form button[type='submit']").text("Alterar TCC");
      $(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroTCC.php" id="limpar" class="btn btn-danger"> Limpar </a>');

      $(".cadastro form input[name='idTCC']").remove();
      $(".cadastro from input[name='localPDFOriginal']").remove();
      $(".cadastro form").append('<input type="hidden" name="idTCC" value="'+id+'">');
      $(".cadastro form").append('<input type="hidden" name="localPDFOriginal" value="'+tcc.localPDF+'">');



    }

  }).fail(function(jqXHR, textStatus, errorThrown){
      // Acessar informações sobre o erro
      console.log("Erro na requisição AJAX: "+textStatus);

      // Verificar se o erro é um erro customizado
      console.log("Erro customizado: "+jqXHR.responseText);
  });

}

$(document).ready(function(){
    
    // Buscar tcc por tipo
    $("#buscarNome").on("input", function(){
      var busca = $(this).val();

      var tipo = $("select[name='busca'] option:selected").val();
      console.log(busca);
      $.post("../alterarCadastros/alterarTCC.php?OP=2", {BUSCA: busca, TIPO: tipo}, function(data){
        $("#alterar").html(data);
      });
    });

    // Acompanhar as alterações no campo de texto usando o evento input
    $('#buscaAluno').on('input', function() {
      // Obter o valor do texto digitado no campo
      var textoDigitado = $(this).val();

      textoDigitado = $.param({BUSCA:textoDigitado});
    
    // Buscar os alunos que correspondem ao texto digitado
    $('#autor').load('../visao/selectAluno.php?'+textoDigitado);

    });

    // Busca orientadores que correspondem ao nome digitado
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

        //console.log('ID: '+IDS);

        var IDString = IDS.join(',');

        textoDigitado = $.param({ID:IDString,BUSCA:textoDigitado});

        console.log(textoDigitado);
      }

      $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);

    });

    // Seleciona a categoria principal e carrega as categorias secundarias
    $('#categoriaPrincipal').change(function(event){
      var opcaoSelecionada = $(this).val();
      var texto = $(this).find('option:selected').text();
      
      categoriaSelecionada = opcaoSelecionada;

      var textoDigitado = $.param({ID:opcaoSelecionada,BUSCA:''});
      if(!event.isTrigger){
        $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);
      }
      if(!$('#categoriasSalvas input[value="'+opcaoSelecionada+'"]').length && opcaoSelecionada > 0){
        $('#categoriasSalvas').append('<div class="d-inline-block m-2"><input class="form-check-input" type="checkbox" name="categorias[]" value="'+opcaoSelecionada+'" checked onclick="removerCategoria(this)"> <label class="form-check-label">'+texto+'</label></div>');
      }

    });

    // Seleciona a categoria secundaria e carrega as categorias subsecundarias
    $('#categoriaSecundaria').change(function(event){

      var opcaoSelecionada = $(this).val();
      var texto = $(this).find('option:selected').text();
      
      if(!$('#categoriasSalvas input[value="'+opcaoSelecionada+'"]').length && opcaoSelecionada > 0){

        $('#categoriasSalvas').append('<div class="d-inline-block m-2"><input class="form-check-input" type="checkbox" name="categorias[]" value="'+opcaoSelecionada+'" checked onclick="removerCategoria(this)"> <label class="form-check-label">'+texto+'</label></div>');
      
        var textoDigitado = $.param({ID:opcaoSelecionada,BUSCA:''});
        if(!event.isTrigger){
         $('#categoriaSecundaria').load('../visao/selectCategoriaSecundaria.php?'+textoDigitado);
        }
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