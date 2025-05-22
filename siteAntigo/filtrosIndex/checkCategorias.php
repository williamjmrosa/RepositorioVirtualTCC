<?php
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/categoria.class.php';

function checkCategoriasPrincipal($array){
    echo '<summary>Categoria Principal</summary>';
    if(count($array) > 0){
        
    foreach($array as $categoria){
        echo "<label class='container-filtro m-2'> $categoria->nome <input class='categoria' name='listarCategoriasPrincipal[]' type='checkbox' value='$categoria->idCategoria' onClick='categoriaPrincipalSelecionados(this)'><span class='filtro'></span></label>";
    }
    }else{
        echo "<div class='alert alert-warning m-2' role='alert'>Nenhuma Categoria encontrada</div>";
    }
}

function checkCategoriasSecundaria($array){
    echo '<summary>Categoria Secundaria</summary>';
    if(count($array) > 0){
        foreach($array as $categoria){
            echo "<label class='container-filtro m-2'> $categoria->nome <input class='categoria' name='listarCategoriasSecundaria[]' type='checkbox' value='$categoria->idCategoria' onClick='categoriaPrincipalSelecionados(this)'><span class='filtro'></span></label>";
        }
    }else{
        echo "<div class='alert alert-warning m-2' role='alert'>Nenhuma Categoria encontrada</div>";
    }
}

if(isset($_GET['OP'])){
    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($op){
        // Listar Cursos Principal
        case 1:
            $categoriaDAO = new CategoriaDAO();
            $array = $categoriaDAO->listarCategoriaPrincipal();

            checkCategoriasPrincipal($array);

            break;

        // Listar Categoria Secundaria
        case 2:
            $categoriaDAO = new CategoriaDAO();
            if(isset($_GET['ID'])){
                $id = filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);

                $array = $categoriaDAO->listarSubCategoriasPelaPrincipal($id);
            }else{
                $array = $categoriaDAO->listarSubCategorias();
            }

           checkCategoriasSecundaria($array);

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

            checkCategoriasPrincipal($array);

        break;

        // Buscar Categoria Secundaria
        case 4:
            $categoriaDAO = new CategoriaDAO();
            if(isset($_GET['BUSCA']) && isset($_GET['ID'])){
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $id = filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);

                $array = $categoriaDAO->buscarSubCategoriasPorNomeRelacionadoPrincipal($busca, $id);

            }elseif(isset($_GET['BUSCA'])){
                
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $array = $categoriaDAO->buscarSubCategoriasPorNome($busca);

            }elseif(isset($_GET['ID'])){
                $id = filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT);

                $array = $categoriaDAO->listarSubCategoriasPelaPrincipal($id);
            }else{
                $array = $categoriaDAO->listarSubCategorias();
            }

            checkCategoriasSecundaria($array);

        break;


        default:
            echo '<summary>Categoria</summary>';
            $categoriaDAO = new CategoriaDAO();
            checkCategoriasPrincipal($categoriaDAO->listarCategoria());
            break;

    }

}else{
    $categoriaDAO = new CategoriaDAO();
    checkCategoriasPrincipal($categoriaDAO->listarCategoria());
}

?>