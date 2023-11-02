<?php
include_once '../dao/cursodao.class.php';
include_once '../Modelo/curso.class.php';


function checkCursos($array){
    echo '<summary>Curso</summary>';
    foreach($array as $curso){
        echo "<label class='container-filtro m-2'> $curso->nome <input type='checkbox' name='listarCursos[]' value='$curso->idCurso' onClick='cursoSelecionados(this)'><span class='filtro'></span></label>";
    }
}

if(isset($_GET['OP'])){
    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($op){
        // Listar Cursos
        case 1:
            $cursoDAO = new CursoDAO();
            
            if(isset($_GET['ID'])){
                $id = filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);
                $array = $cursoDAO->buscarCursoDeCampus($id);
            }else{
                $array = $cursoDAO->listarCursos();
            }
            
            checkCursos($array);

            break;

        // Buscar Curso
        case 2:
            $cursoDAO = new CursoDAO();
            if(isset($_GET['BUSCA']) && isset($_GET['ID'])){
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $id = filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);

                $array = $cursoDAO->buscarCursoPorNomeCampus($busca, $id);

            }elseif(isset($_GET['BUSCA'])){
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $array = $cursoDAO->buscarCursoPorNome($busca);
                
            }else{
                $array = $cursoDAO->listarCursos();
            }

            checkCursos($array);

            break;

        default:
            $cursoDAO = new CursoDAO();
            checkCursos($cursoDAO->listarCursos());
    }

}else{
    $cursoDAO = new CursoDAO();
    checkCursos($cursoDAO->listarCursos());
}
?>