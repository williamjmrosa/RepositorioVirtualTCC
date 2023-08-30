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

$(document).ready(function(){

	$("#cursos").click(function(){
		selecionarTodosOsCursos();
	});

	/*
	$("#lista option").click(function(){
		var select = $(this);
		var texto = select.text();
		var valor = select.val();
		var opcao = '<option value="'+valor+'" selected onclick="cursos(this)">'+texto+'</otion>';
		//var opcao = '<div><label class="form-label">'+texto+'</label><input type="hidden" name="cursos[]" value="'+
		//valor+'"> <a class="btn btn-primary" id="remover" onclick="cursos(this)">X</a> </div>';
		$('#cursos').append(opcao);
		select.remove();
	});

	$("#remover").click(function(){
		//var select = $(this);
		alert("aqui");
	});*/

	/*
	$("#cursos").click(function(){
		var select = $(this).select();
		var texto = $(select).text();
		var valor = $(select).val();
		var opcao = '<option value="'+valor+'" selected>'+texto+'</otion>';
		$('#lista').append(opcao);
		alert($(this).val());
	});
*/
	
	/*
	
	$("[type=checkbox]").click(function(){
		var tipo = $(this).attr("id");//is(":checked" );
		
		if(tipo == "filtro1" && $(this).is(":checked")){
			$("#listaLivros").load("../canoas.html");
			alert("filtrou");
		}
		
	});
	*/
});