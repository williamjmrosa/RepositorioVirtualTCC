<?php
include_once '../dao/cursodao.class.php';
include_once '../Modelo/curso.class.php';

if (isset($_GET['BUSCA'])) {
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $cursoDAO = new CursoDAO();

    $curso = $cursoDAO->buscarCursoAluno($busca);
    
    echo '<label for="cursoNome" class="form-label">Curso</label>';
    echo '<input type="hidden" id="curso" name="curso" value="' . $curso->idcurso . '">';
    echo '<input type="text" disabled class="form-control" id="cursoNome" placeholder="Curso" value="' . $curso->nome . '">';
}
