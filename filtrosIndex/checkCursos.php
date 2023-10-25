<?php
include_once '../dao/cursodao.class.php';
include_once '../Modelo/curso.class.php';

if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $cursoDAO = new CursoDAO();
        echo '<summary>Curso</summary>';
    foreach($cursoDAO->buscarCursoPorNome($busca) as $curso){
        echo "<label class='container-filtro'> $curso->nome <input type='checkbox' name='listarCursos[]' value='$curso->idCurso'><span class='filtro'></span></label>";
    }

}else{
    echo '<summary>Curso</summary>';
    $cursoDAO = new CursoDAO();
    foreach($cursoDAO->listarCursos() as $curso){
        echo "<label class='container-filtro'>$curso->nome <input type='checkbox' name='listarCursos[]' value='$curso->idCurso'><span class='filtro'></span></label>";
    }
}
?>