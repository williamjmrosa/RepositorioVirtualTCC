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
        echo "<option value='0'>Nenhum Campus cadastrado</option>";
    }
}


?>