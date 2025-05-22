<?php
include_once '../dao/campusdao.class.php';
include_once '../Modelo/campus.class.php';


if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $campusDAO = new CampusDAO();
        echo '<summary>Campus</summary>';
        $array = $campusDAO->buscarCampusPorNome($busca);
    if(count($array) > 0){
        foreach($array as $campus){
            echo "<label class='container-filtro'>$campus->nome <input type='checkbox' name='listarCampus[]' value='$campus->idCampus' onClick='campusSelecionados(this)'><span class='filtro'></span></label>";
        }
    }else{
        echo "<div class='alert alert-warning m-2' role='alert'>Nenhum Campus encontrado</div>";
    }
}else{
    echo '<summary>Campus</summary>';
    $campusDAO = new CampusDAO();
    $array = $campusDAO->listarCampus();
    if(count($array) > 0){
        foreach($array as $campus){
            echo "<label class='container-filtro'>$campus->nome <input type='checkbox' name='listarCampus[]' value='$campus->idCampus' onClick='campusSelecionados(this)'><span class='filtro'></span></label>";
        }
    }else{
        echo "<div class='alert alert-warning m-2' role='alert'>Nenhum Campus encontrado</div>";
    }
}
?>