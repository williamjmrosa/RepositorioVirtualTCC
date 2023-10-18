<?php
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/categoria.class.php';

function mostrarCategorias($categorias){
    if(count($categorias) > 0){
          foreach ($categorias as $categoria) {
               echo '<option value="' . $categoria->idCategoria . '">' . $categoria->nome . '</option>';
          }
     }else{
         echo '<option value="0">Nenhuma categoria encontrada</option>';
     }
}

if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $cDAO = new CategoriaDAO();
    
    if($busca != ""){
    $categorias = $cDAO->buscarCategoriaPrincipalPorNome($busca);

    mostrarCategorias($categorias);

    }else{
        $categorias = $cDAO->listarCategoriaPrincipal();
        mostrarCategorias($categorias);
    }

}

?>