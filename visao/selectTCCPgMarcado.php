<?php 
session_start();
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

if (isset($_SESSION['usuario']) && isset($_GET['tipoCheck'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    $tipoCheck = filter_var($_GET['tipoCheck'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $idInstituicao = isset($_GET['idInstituicao']) ? filter_var($_GET['idInstituicao'], FILTER_SANITIZE_NUMBER_INT) : null;
    $idCurso = isset($_GET['idCurso']) ? filter_var($_GET['idCurso'], FILTER_SANITIZE_NUMBER_INT) : null;
    $idProfessor = $matricula = isset($_GET['matricula']) ? filter_var($_GET['matricula'], FILTER_SANITIZE_NUMBER_INT) : null;

    $div = isset($_GET['div']) ? filter_var($_GET['div'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

    if($tipoCheck == 'favorito'){
        $fDAO = new FavoritoDAO();
     
        switch ($tipo) {
            case 'Aluno':
                
               if($div == 'divTCC'){
                    $tccs = $fDAO->listarFavoritosAluno($user->matricula);
                    divTCCs($tccs);
                }

                break;
            case 'Professor':
                if($div == 'divTCC'){
                    $tccs = $fDAO->listarFavoritosProfessor($user->matricula);
                    divTCCs($tccs);
                }

                break;
            case 'Visitante':
                if($div == 'divTCC'){
                    $tccs = $fDAO->listarFavoritosVisitante($user->email);
                    divTCCs($tccs);
                }
                break;
            case 'Bibliotecario':
            case 'Adm':

                if($div == 'divTCC'){
                    $tccs = $fDAO->listarFavoritos();
                    divTCCs($tccs);
                }

                break;
        }
        
    }else if($tipoCheck == 'indicado'){
        $iDAO = new IndicacaoDAO();

        switch ($tipo) {
            case 'Aluno':

                if($div == 'divProfessor'){
                    $professores = $iDAO->listarProfessores($user->campus, $user->curso);
                    divProfessores($professores);
                }elseif ($div == 'divTCC'){
                    $tccs = $iDAO->listarIndicacoes($idInstituicao, $idCurso, $idProfessor);
                    divTCCs($tccs);
                }elseif($div == 'divIndicadoParaAluno'){
                    //$alunos = $iDAO->listar
                }

                break;
            case 'Professor':
                if($div == 'divTCC'){
                    $tccs = $iDAO->listarIndicacoes($idInstituicao, $idCurso, $user->matricula);
                    divTCCs($tccs);
                }elseif($div == 'divCurso'){
                    $cursos = $iDAO->listarCursos($idInstituicao, $idCurso, $user->matricula);
                    divCursos($cursos);
                }elseif($div == 'divCampus'){
                    $instituicoes = $iDAO->listarCampus($idInstituicao, $idCurso, $user->matricula);
                    divCampus($instituicoes);
                }elseif($div == 'divTCCIndicadoParaAluno'){
                    $tccs = $iDAO->listarTCCsIndicadosParaAluno($matricula);
                    divIndicadoParaAlunos($tccs, $tipo);  
                }elseif($div == 'divListarAlunoIndicado'){
                    $alunos = $iDAO->listarAlunosIndicados($user->matricula);
                    divListarAlunoIndicados($alunos);
                }
                break;
            case 'Visitante':
                echo "<div class='alert alert-warning m-2' role='alert'>Visitante não pode ver as indicações</div>";
                break;
            case 'Bibliotecario':
            case 'Adm':
                if($div == 'divProfessor'){
                    $professores = $iDAO->listarProfessores($idCurso, $idInstituicao);
                    divProfessores($professores);
                }elseif($div == 'divCurso'){
                    $cursos = $iDAO->listarCursos($idInstituicao, $idCurso);
                    divCursos($cursos);
                }elseif($div == 'divCampus'){
                    $instituicoes = $iDAO->listarCampus();
                    divCampus($instituicoes);
                }elseif($div == 'divTCC'){
                    $tccs = $iDAO->listarIndicacoes($idInstituicao, $idCurso, $idProfessor);
                    divTCCs($tccs);
                }
                break;
        }
    }else{
        $erros[] = "Voce precisa estar logado para acessar favoritos/indicados";
        $_SESSION['erros'] = $erros;
        header('location:../index.php');
    }
    
} else {
    if(!isset($_SESSION['usuario'])) {
    $erros[] = "Voce precisa estar logado para acessar favoritos/indicados";
    $_SESSION['erros'] = $erros;
    header('location:../index.php');
    }else{
        echo '<div class="alert alert-warning m-2" role="alert">Acesso negado</div>';
    }
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
    <select class="form-select" name="tcc" id="tcc" size="8">
        <option selected>Selecione um TCC</option>
        <?php
        foreach ($tccs as $tcc) {
            echo "<option value='$tcc->idTCC' onclick='verTCC(this)'>$tcc->titulo</option>";
        }
        ?>
    </select>
<?php }

function divIndicadoParaAlunos($tccs, $tipo = null){
    if($tipo == 'Aluno'){?>
        <label class="form-label-inline" for="tccIndicadoMim">TCC(s) Indicados para mim por um professor</label>
<?php }else{?>
        <label class="form-label-inline" for="tccIndicadoAluno">TCC(s) Indicados para alunos</label>
<?php } ?>
        <select class="form-select" name="tccIndicadoMim" id="tccIndicadoMim" size="3" onchange="gerarIdIndicaAluno(this)">
            <option selected>Selecione um TCC</option>
            <?php
            foreach ($tccs as $tcc) {
                $titulo = $tcc['titulo'];
                $idIndicaAluno = $tcc['idIndicaAluno'];
                $idTCC = $tcc['idTCC'];
                echo "<option value='$idIndicaAluno' onclick='verTCC(this, $idTCC)'>$titulo</option>";
            }
            ?>
    </select>
    <button disabled="disabled" class="btn btn-primary mt-1" id="excluirIndicacaoAluno" value="" onclick="excluirIndicacaoAluno(this)">Excluir Indicação Aluno</button>
  
<?php }

function divListarAlunoIndicados($alunos){
    ?>
    <label for="tccIndicadoAluno">TCC(s) Alunos que recebeu indicação</label>
    <select class="form-select" name="tccIndicadoAluno" id="tccIndicadoAluno" onchange="atualizarDivTCCIndicadoParaAluno(this)">
        <option selected>Selecione um Aluno</option>
        <?php
        foreach ($alunos as $aluno) {
            echo "<option value='$aluno->matricula'>$aluno->nome</option>";
        }
        ?>
    </select>
<?php }

?>