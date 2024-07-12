// Carregar a lista de campus para alterar ou mudar o status
$("#alterar").load("../alterarCadastros/alterarCampus.php?OP=1");
// Carregar o menu de login ao carregar a tela
$("#login").load("../menuLogin/menuLogin.php");

// Função para add a lista de Cursos selecionados
function lista(option){
	var select = $(option);
	var texto = select.text();
	var valor = select.val();
	var opcao = '<option onclick="cursos(this)" selected="selected" value="'+valor+'">'+texto+'</otion>';
	$('#cursos').append(opcao);
	select.remove();
}

// Função para remover um curso da lista de Cursos selecionados
function cursos(option){
	var select = $(option);
	var texto = select.text();
	var valor = select.val();
	var opcao = '<option value="'+valor+'" onclick="lista(this)">'+texto+'</otion>';
	$('#lista').append(opcao);
	select.remove();
	
}

// Função para selecionar todos os cursos
function selecionarTodosOsCursos(){
	$("#cursos option").each(function(){
		var select = $(this);
		var texto = select.text();
		var valor = select.val();
		var opcao = '<option onclick="cursos(this)" selected="selected" value="'+valor+'">'+texto+'</otion>';
		select.remove();
		$('#cursos').append(opcao);
		
	});
}

// Função para preencher o formulário com os dados do campus
function preencherForm(option, event) {
	event.preventDefault();
	var select = $(option);
	var id = select.attr("href");

	$.ajax({
		url: '../alterarCadastros/alterarCampus.php?OP=3&id=' + id,
		dataType: 'json'
	}).done(function (response) {
		var campus = response;
		
		if(response.error){
			alert(response.error);
		}else{
			// Receber os dados do campus e preencher o formulário com os dados do campus
			$('#nome').val(campus.nome);
			$("#cursosCadastrados").empty();
			campus.cursos.forEach(curso => {
				$('#cursosCadastrados').append('<div class="row g-3"><div class="col-auto"><label class="form-label">Curso: </label></div><div class="col-auto"><input class="form-control" disabled type="text" value="'+curso.nome+'"/> </div><div class="col-auto"> <a href="../controle/campus-controle.php?OP=4&idCurso='+curso.idCurso+'&idCampus='+campus.idCampus+'" class="btn btn-danger ms-1"> Remover </a></div></div>');

				$("select[name=lista]").find("option[value="+curso.idCurso+"]").remove();

			});

			$(".cadastro form button[type='submit']").text("Alterar Campus");

			$("#limpar").remove();

			$(".cadastro form button[type='submit']").parent().append('<a href="../visao/telaCadastroCampus.php" id="limpar" class="btn btn-danger"> Limpar </a>');

			$(".cadastro form").attr('action', '../controle/campus-controle.php?OP=2');

			$(".cadastro form input[name=id]").remove();

			

			$(".cadastro form").append('<input type="hidden" name="id" value="'+campus.idCampus+'">');

			$('.cadastro form').show();

		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		// Acessar informações sobre o erro
		console.log("Erro na requisição AJAX");
		// Verificar se o erro é um erro customizado
		if (jqXHR.responseText) {
			console.log("Erro customizado: " + jqXHR.responseText);
		}
	})
}

$(document).ready(function(){

	// Selecionar todos os Cursos
	$("#cursos").click(function(){
		selecionarTodosOsCursos();
	});

	// Função para listar os campus a partir do nome do campus
	$("#buscarNome").on("input", function(){

		var nome = $(this).val();

		var parametros = $.param({
			OP: 2,
			BUSCA: nome
		});

		$("#alterar").load("../alterarCadastros/alterarCampus.php?"+parametros);

	});
	
});