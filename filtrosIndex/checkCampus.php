<?php
include_once '../dao/campusdao.class.php';
include_once '../Modelo/campus.class.php';


if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $campusDAO = new CampusDAO();
        echo '<summary>Campus</summary>';
    foreach($campusDAO->buscarCampusPorNome($busca) as $campus){
        echo "<label class='container-filtro'>IFRS $campus->nome <input type='checkbox' name='listarCampus[]' value='$campus->idCampus'><span class='filtro'></span></label>";
    }
}else{
    echo '<summary>Campus</summary>';
    $campusDAO = new CampusDAO();
    foreach($campusDAO->listarCampus() as $campus){
        echo "<label class='container-filtro'>IFRS  $campus->nome <input type='checkbox' name='listarCampus[]' value='$campus->idCampus'><span class='filtro'></span></label>";
    }
}
?>