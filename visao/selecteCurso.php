<?php
include_once '../dao/cursodao.class.php';
include_once '../Modelo/curso.class.php';


if(isset($_GET['ID'])){
    $id = $_GET['ID'];
    $cursoDAO = new CursoDAO();
    $cursos = $cursoDAO->buscarCursoCampus($id);

    if(is_array($cursos)){
        foreach ($cursos as $curso) {
      
            echo "<option value='$curso->idCurso'>$curso->nome</option>";
      
        }
    }else{
        echo "<option value=''>Nenhum Campus cadastrado</option>";
    }
}else if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $cursoDAO = new CursoDAO();
    $cursos = $cursoDAO->buscarCursosPorNome($busca);
        echo "<option value='null' selected>Selecione um Curso</option>";
    if(is_array($cursos)){
        foreach ($cursos as $curso) {
            echo "<option onclick='lista(this)' value='$curso->idCurso'>$curso->nome | ". $curso->mostrarEnsino()."</option>";
        }
    }else{
        echo "<option value=''>Nenhum Curso cadastrado</option>";
    }
}


?>