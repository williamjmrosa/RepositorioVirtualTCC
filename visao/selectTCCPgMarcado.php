<?php
include_once '../dao/favoritodao.class.php';
include_once '../dao/indicacaodao.class.php';
include_once '../dao/campusdao.class.php';
include_once '../dao/cursodao.class.php';
include_once '../dao/professordao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';
session_start();
if (isset($_SESSION['usuario']) && isset($_GET['tipoCheck']) && !isset($_GET['div'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);
    
    $tipoCheck = filter_var($_GET['tipoCheck'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $tipoCheck == 'favorito' ? $fDAO = new FavoritoDAO() : $iDAO = new IndicacaoDAO();
    if ($tipo == 'Aluno' && $tipoCheck == 'favorito') {
        $tccs = $fDAO->listarFavoritosAluno($user->matricula);
    } elseif ($tipo == 'Professor' && $tipoCheck == 'favorito') {
        $tccs = $fDAO->listarFavoritosProfessor($user->matricula);
    } elseif ($tipo == 'Visitante' && $tipoCheck == 'favorito') {
        $tccs = $fDAO->listarFavoritosVisitante($user->email);
    } else if ($tipo == 'Bibliotecario' || $tipo == 'Adm' && $tipoCheck == 'favorito') {
        $tccs = $fDAO->listarFavoritos();
    } else {
        $tccs = $iDAO->listarIndicacoes();
    }

    divTCCs($tccs);

} elseif (isset($_SESSION['usuario']) && isset($_GET['div'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);
    $div = filter_var($_GET['div'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $iDAO = new IndicacaoDAO();

    if ($tipo == 'Bibliotecario' || $tipo == 'Adm' && $div == 'divCampus') {
        
        if(isset($_GET['tipoCheck'])){
            $tipoCheck = filter_var($_GET['tipoCheck'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($tipoCheck == 'indicado'){
                $instituicoes = $iDAO->listarCampus();
            }else{
                $campusDAO = new CampusDAO();
                $instituicoes = $campusDAO->listarCampus();
            }
            
        }else{
            $campusDAO = new CampusDAO();
            $instituicoes = $campusDAO->listarCampus();
        }
        
        divCampus($instituicoes);
    } elseif ($tipo == 'Bibliotecario' || $tipo == 'Adm' && $div == 'divCurso') {
        
        if(isset($_GET['tipoCheck'])){
            $tipoCheck = filter_var($_GET['tipoCheck'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($tipoCheck == 'indicado' && isset($_GET['idInstituicao']) && isset($_GET['idCurso'])){
                $idInstituicao = $_GET['idInstituicao'] != null ? filter_var($_GET['idInstituicao'], FILTER_SANITIZE_NUMBER_INT) : null;
                $idCurso = $_GET['idCurso'] != null ? filter_var($_GET['idCurso'], FILTER_SANITIZE_NUMBER_INT) : null;
                $cursos = $iDAO->listarCursos($idInstituicao, $idCurso);
            }else{
                $cursoDAO = new CursoDAO();
                $cursos = $cursoDAO->listarCursos();
            }
        }else{
            $cursoDAO = new CursoDAO();
            $cursos = $cursoDAO->listarCursos();
        }

        divCursos($cursos);
    }elseif($div == 'divProfessor'){

        if($tipo == 'Aluno'){
            $curso = $user->curso;
            $instituicao = $user->campus;
        }elseif(isset($_GET['idInstituicao']) && isset($_GET['idCurso'])){
            $instituicao = $_GET['idInstituicao'] != null ? filter_var($_GET['idInstituicao'], FILTER_SANITIZE_NUMBER_INT) : null;
            $curso = $_GET['idCurso'] != null ? filter_var($_GET['idCurso'], FILTER_SANITIZE_NUMBER_INT) : null;    
        }else{
            $instituicao = null;
            $curso = null;
        }
        $professores = $iDAO->listarProfessoresAluno($curso, $instituicao);

        divProfessores($professores); 
    }elseif($div == 'divTCC' && isset($_GET['tipoCheck']) && isset($_GET['idInstituicao']) && isset($_GET['idCurso'])){
        $idProfessor = isset($_GET['matricula']) && $_GET['matricula'] != null ? filter_var($_GET['matricula'], FILTER_SANITIZE_NUMBER_INT) : null;
        $tipoCheck = filter_var($_GET['tipoCheck'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idInstituicao = $_GET['idInstituicao'] != null ? filter_var($_GET['idInstituicao'], FILTER_SANITIZE_NUMBER_INT) : null;
        $idCurso = $_GET['idCurso'] != null ? filter_var($_GET['idCurso'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        $tccs = $iDAO->listarIndicacoes($idInstituicao, $idCurso,$idProfessor);
        divTCCs($tccs);
    }

} else {
    echo "<option value=''>Nenhum favorito encontrado</option>";
}

function divProfessores($professores)
{
    if(count($professores) > 0){
        echo "<div class='col-3'><label class='form-label-inline' for='prof'>Professor</label></div>
        <div class='col-8'><select class='form-select' name='prof' id='prof' onchange='atualizarDivProfessor(this)'>";  
        echo "<option selected>Selecione um professor</option>"; 
        foreach ($professores as $professor) {
            echo "<option value='$professor->matricula'>$professor->nome</option>";
        }
        echo "</select></div>";
    }else{
        echo "<div class='col-3'><label class='form-label-inline' for='prof'>Professor</label></div>
        <div class='col-8'><select class='form-select' name='prof' id='prof'>";  
        echo "<option selected>Selecione um professor</option>"; 
        echo "</select></div>";
    }
}

function divCampus($instituicoes)
{ ?>
    <label class="form-label-inline" for="inst">Instituição</label>
    <select class="form-select" name="inst" id="inst" onchange="atualizarDivInstituicao(this)">
        <option selected>Selecione uma instituição</option>
        <?php
        foreach ($instituicoes as $instituicao) {
            echo "<option value='$instituicao->idCampus'>$instituicao->nome</option>";
        }
        ?>
    </select>
<?php
}
function divCursos($cursos)
{ ?>
    <label class="form-label-inline" for="cur">Curso</label>
    <select class="form-select" name="cur" id="cur" onchange="atualizarDivCurso(this)">
        <option selected>Selecione um curso</option>
        <?php
        foreach ($cursos as $curso) {
            echo "<option value='$curso->idCurso'>$curso->nome</option>";
        }
        ?>
    </select>
<?php
} 
function divTCCs($tccs){?>
    <label class="form-label-inline" for="tcc">TCC</label>
    <select class="form-select" name="tcc" id="tcc" size="10">
        <option selected>Selecione um TCC</option>
        <?php
        foreach ($tccs as $tcc) {
            echo "<option value='$tcc->idTCC' onclick='verTCC(this)'>$tcc->titulo</option>";
        }
        ?>
    </select>
<?php }?>