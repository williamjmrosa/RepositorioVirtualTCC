var idInstituicao = null;
var idCurso = null;
//Função para adicionar tcc a lista de favoritos de um usuario
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
//Função para remover tcc da lista de favoritos de um usuario
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

//Funcção para preencher o formulário com id do tcc
function clicarIndicar(btn,idTCC){
    var modal = $("#indicar");
    modal.find("#tcc").val(idTCC);
}

//Função para salvar a indicação
function SalvarIndicar(){
    $("#formIndicar").submit();
}

//Função para carregar os cursos de acordo a instituição para o select
function carregarCursos(btn){
    var option = $(btn);
    $("#curso").load("../visao/selecteCurso.php?ID="+option.attr("value"),function(){
        $("#curso").prepend("<option value='' selected>Selecione o curso</option>");
    });
    idInstituicao = option.attr("value");
    idCurso = null;
    
}

//Função para remover o aluno selecionado
function removerAluno(btn){
    var option = $(btn);
    option.parent().remove();
}

$(document).ready(function(){
    $("#btn-indicar").click(function(){
        var modal = $("#indicar");
        
        //modal.find(".modal-body").append("Deseja indicar este TCC?");
        
    });
    // Buscar Alunos de ums instituição e curso
    $("#curso").change(function(){
        idCurso = $(this).val();
        if(idInstituicao != null && idCurso != null){
            $("#alunos").load("../controle/indicar-controle.php?OP=3&campus="+idInstituicao+"&curso="+idCurso);
        }
    });

    // Adicionar Aluno selecionado a lista de alunos para indicar
    $("#alunos").change(function(){
        var idAluno = $(this).val();
        var nome = $(this).find("option:selected").text();
        if($("#"+idAluno).length == 0){
            var addAluno = '<div class="form-check"><input class="form-check-input" id="'+idAluno+'" type="checkbox" value="'+idAluno+'" name="aluno[]" checked onChange="removerAluno(this)"/><label class="form-check-label" for="'+idAluno+'">'+nome+'</label></div>';
            $("#alunoSelecionado").append(addAluno);
        }
    });
    
    // Buscar instituição
    $('#searchInputInstituicao').on('input', function() {
      var searchText = $(this).val().toLowerCase();
      $('#instituicao option').each(function() {
        var optionText = $(this).text().toLowerCase();
        $(this).toggle(optionText.indexOf(searchText) > -1);
      });
    });

    // Buscar curso
    $("#searchInputCurso").on("input", function() {
      var searchText = $(this).val().toLowerCase();
      $("#curso option").each(function() {
        var optionText = $(this).text().toLowerCase();
        $(this).toggle(optionText.indexOf(searchText) > -1);
      });
    });

    // Buscar alunos
    $("#searchInputAluno").on("input", function() {
        var searchText = $(this).val().toLowerCase();
        $("#alunos option").each(function() {
          var optionText = $(this).text().toLowerCase();
          $(this).toggle(optionText.indexOf(searchText) > -1);
        });
      });

    // Função para salvar a indicação
    $('#formIndicar').submit(function(event) {
      event.preventDefault();
      $.ajax({
          type: 'POST',
          url: '../controle/indicar-controle.php?OP=1',
          data: $(this).serialize()
      }).done(function(response) {
        resposta = JSON.parse(response);
        var erros = resposta.erros;
        var alerta = $("#indicar").find(".modal-body");
        if(!alerta.find("#div-alert-modal").length){
            console.log("alerta nao existe");
            
            alerta.prepend("<div id='div-alert-modal' class='alert alert-danger' role='alert'><button type='button' class='btn-close float-end' data-bs-dismiss='alert' aria-label='Close'></button><p class='msg'></p></div>");
                
        }else{
            console.log("alerta ja existe");
        }

        if(resposta.erros){
            alerta.find("#div-alert-modal").attr("class", "alert alert-danger");
            alerta.find("#div-alert-modal").find(".msg").empty();
            $.each(erros, function(index, value) {
                console.log(value);
                alerta.find("#div-alert-modal").find(".msg").append(value+"<br>");
            });
        }else{
            alerta.find("#div-alert-modal").attr("class","alert alert-success");
            alerta.find("#div-alert-modal").find(".msg").empty();
            alerta.find("#div-alert-modal").find(".msg").append(resposta);
            //alert(resposta);
            console.log(resposta);
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        
            console.log("Erro na requisição AJAX:");
            console.log("Status: " + textStatus);
            console.log("Erro: " + errorThrown);

          if(jqXHR.responseText){
              console.log("Erro customizado: " + jqXHR.responseText);
          }
      });
      
    });

});