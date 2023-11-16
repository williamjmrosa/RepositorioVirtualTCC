<?php
include_once '../dao/campusdao.class.php';
include_once '../Modelo/campus.class.php';

if (isset($_GET['BUSCA'])) {
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $campusDAO = new CampusDAO();
    if ($busca != "") {
        $campus = $campusDAO->buscarCampusAluno($busca);

        echo '<label for="campusNome" class="form-label">Campus</label>';
        echo '<input type="hidden" id="campus" name="campus" value="' . $campus->idcampus . '">';
        echo '<input type="text" disabled class="form-control" id="campusNome" placeholder="Campus" value="' . $campus->nome . '">';
        
    }
}
