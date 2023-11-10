$("#alterar").load("../alterarCadastros/alterarCampus.php?OP=1");

function lista(option){
	var select = $(option);
	var texto = select.text();
	var valor = select.val();
	var opcao = '<option onclick="cursos(this)" selected="selected" value="'+valor+'">'+texto+'</otion>';
	$('#cursos').append(opcao);
	select.remove();
	//$(select).find(condicao).remove();
}


function cursos(option){
	var select = $(option);
	var texto = select.text();
	var valor = select.val();
	var opcao = '<option value="'+valor+'" onclick="lista(this)">'+texto+'</otion>';
	//alert(select);
	$('#lista').append(opcao);
	select.remove();
	
}

function selecionarTodosOsCursos(){
	$("#cursos option").each(function(){
		var select = $(this);
		var texto = select.text();
		var valor = select.val();
		var opcao = '<option onclick="cursos(this)" selected="selected" value="'+valor+'">'+texto+'</otion>';
		select.remove();
		//$(this).attr('selected', true);
		$('#cursos').append(opcao);
		//alert($(this).text());
		
	});
}

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

	$("#cursos").click(function(){
		selecionarTodosOsCursos();
	});

	$("#buscarNome").on("input", function(){

		var nome = $(this).val();

		var parametros = $.param({
			OP: 2,
			BUSCA: nome
		});

		$("#alterar").load("../alterarCadastros/alterarCampus.php?"+parametros);

	});
	
});