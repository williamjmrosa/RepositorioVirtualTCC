<?php
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/categoria.class.php';

function mostrarCategorias($categorias){
    if(count($categorias) > 0){
          foreach ($categorias as $categoria) {
               echo '<option ondblclick="carragarCategoriaSecundaria(this)" value="' . $categoria->idCategoria . '">' . $categoria->nome . '</option>';
          }
     }else{
         echo '<option value="0">Nenhuma categoria encontrada</option>';
     }
}

function listarSubSubCategoria($categorias){
    $cDAO = new CategoriaDAO();
    $array = $categorias;
    foreach ($categorias as $categoria) {
       
       $subCategorias = $cDAO->listarSubCategoriasPelaPrincipal($categoria->idCategoria);
       if($subCategorias){
           $array1 = listarSubSubCategoria($subCategorias);
           if($array1){
               $array = array_merge($array,$array1);
           }
       }
       
   }

    return $array;
}


if(isset($_GET['BUSCA']) && isset($_GET['ID'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
    $ID = filter_var($_GET['ID'], FILTER_SANITIZE_SPECIAL_CHARS);
    $cDAO = new CategoriaDAO();

    if($busca != "" && strpos($ID, ',') !== false){
        echo 'ID: ' . $ID;
        $categorias = $cDAO->buscarSubCategoriasPorNomeRelacionadoPrincipal($busca,$ID);
        
    }elseif($busca != ""){
        $categorias = $cDAO->buscarSubCategoriasPorNomeRelacionadoPrincipal($busca,$ID);
    }else{
        $categorias = $cDAO->listarSubCategoriasPelaPrincipal($ID);
        $categorias = listarSubSubCategoria($categorias);

    }

    mostrarCategorias($categorias);
}elseif(isset($_GET['BUSCA'])){

    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
    $cDAO = new CategoriaDAO();

    if($busca != ""){
        $categorias = $cDAO->buscarSubCategoriasPorNome($busca);
    }else{
        $categorias = $cDAO->listarSubCategorias();
    }
    mostrarCategorias($categorias);
}else{
    $cDAO = new CategoriaDAO();
    $categorias = $cDAO->listarSubCategorias();
    mostrarCategorias($categorias);
}
?>