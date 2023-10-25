<?php
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/categoria.class.php';

function checkCategorias($array){
    foreach($array as $categoria){
        echo "<label class='container-filtro'> $categoria->nome <input name='listarCategorias[]' type='checkbox' value='$categoria->idCategoria'><span class='filtro'></span></label>";
    }
}

if(isset($_GET['OP'])){
    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($op){
        // Listar Cursos Principal
        case 1:
            $categoriaDAO = new CategoriaDAO();
            $array = $categoriaDAO->listarCategoriaPrincipal();

            echo '<summary>Cursos</summary>';
            checkCategorias($array);

            break;

        // Listar Categoria Secundaria
        case 2:
            $categoriaDAO = new CategoriaDAO();
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $array = $categoriaDAO->listarSubCategoriasPelaPrincipal($id);
            }else{
                $array = $categoriaDAO->listarSubCategorias();
            }

            echo '<summary>Categoria Secundaria</summary>';
            checkCategorias($array);

        break;

        // Buscar Categoria Principal
        case 3:
            $categoriaDAO = new CategoriaDAO();
            if(isset($_GET['BUSCA'])){
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $array = $categoriaDAO->buscarCategoriaPrincipalPorNome($busca);
            }else{
                $array = $categoriaDAO->listarCategoriaPrincipal();
            }



        default:
            echo '<summary>Cursos</summary>';
            $categoriaDAO = new CategoriaDAO();
            foreach($categoriaDAO->listarSubCategorias() as $categoria){
                echo "<label class='container-filtro'> $categoria->nome <input type='checkbox' name='categoria[]' value='$categoria->idCategoria'><span class='filtro'></span></label>";
            }

            break;

    }

}else{
    echo '<summary>Cursos</summary>';
    $categoriaDAO = new CategoriaDAO();
    foreach($categoriaDAO->listarCategoria() as $categoria){
        echo "<label class='container-filtro'> $categoria->nome <input type='checkbox' name='categoria[]' value='$categoria->idCategoria'><span class='filtro'></span></label>";
    }
}

?>