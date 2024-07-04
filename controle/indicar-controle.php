<?php
session_start();
include_once '../dao/indicacaodao.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';

function verSeEAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

if(isset($_GET['OP']) && isset($_SESSION['usuario'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    switch($OP){
        //Cadastrar Indicação
        case 1:
            $erro = array();
            if(isset($_POST['idTCC']) && !empty($_POST['idTCC'])){
                $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $erro[] = "Erro ID para TCC não existe! ou em branco!";
            }
            
            if($tipo == 'Professor'){
                $idUsuario = $user->matricula;
            }else{
                $erro[] = "Erro $tipo não pode indicar!";
            }
            
            if(isset($_POST['instituicao']) && !empty($_POST['instituicao'])){
                $instituicao = filter_var($_POST['instituicao'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $erro[] = "Erro instituicao em branco!";
            }

            if(isset($_POST['curso']) && !empty($_POST['curso'])){
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $erro[] = "Erro cargo em branco!";
            }

            if(isset($_POST['aluno'])){
                $alunos = array();
                foreach ($_POST['aluno'] as $aluno) {
                    $alunos[] = filter_var($aluno, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }else{
                $alunos = null;
            }

            if(count($erro) == 0){
                $iDAO = new IndicacaoDAO();
                $iDAO->cadastrarIndicacao($idTCC, $idUsuario, $instituicao, $curso, $alunos);
            }else{
                if(verSeEAjax()){
                    $resposta['erros'] = $erro;
                    echo json_encode($resposta);
                }else{
                    $_SESSION['erros'] = $erro;
                    header("location:../visao/index.php");
                }
            }

            break;
        //Remover Indicação
        case 2:
            break;
        //Listar Aluno para ser indicado o TCC
        case 3:
            if(isset($_GET['campus']) && !empty($_GET['campus']) && isset($_GET['curso']) && !empty($_GET['curso'])){
                $campus = filter_var($_GET['campus'], FILTER_SANITIZE_NUMBER_INT);
                $curso = filter_var($_GET['curso'], FILTER_SANITIZE_NUMBER_INT);
                $iDAO = new IndicacaoDAO();
                $alunos = $iDAO->alunosParaIndicar($campus, $curso);
                if($alunos){
                    echo '<option selected>Selecione o Aluno</option>';
                    foreach ($alunos as $aluno) {
                        echo '<option value="' . $aluno->matricula . '">' . $aluno->nome . '</option>';
                    }
                }else{
                    echo '<option selected>Nenhum Aluno encontrado</option>';
                }
            }else{
                $erro[] = "Erro campus em branco!";
            }
            break;
        //Excluir Indicação Aluno
        case 4:
            $msg = array();
            if(isset($_POST['idIndicacaoAluno']) && !empty($_POST['idIndicacaoAluno'])){
                $idIndicaAluno = filter_var($_POST['idIndicacaoAluno'], FILTER_SANITIZE_NUMBER_INT);

                $iDAO = new IndicacaoDAO();
                if($iDAO->excluirIndicacaoAluno($idIndicaAluno)){
                    $msg['sucesso'] = "Indicação excluída com sucesso!";
                }else{
                    $msg['erro'] = "Erro ao excluir Indicação!";
                }
            }else{
                $msg['erro'] = "Erro nenhuma Indicação selecionada!";
            }

            if(verSeEAjax()){
                echo json_encode($msg);
            }else{
                $_SESSION['msg'] = $msg;
                header("location:../visao/index.php");
            }

            break;
        default:
            if(verSeEAjax()){
                echo json_encode("Obrigatório selecionar uma opção");
            }else{
                $erros = array();
                $erros[] = "Obrigatório selecionar uma opção";
                $_SESSION['erros'] = $erros;
                header("location:../visao/index.php");
            }
            break;
    }
}else{
    $erros = array();
    if(verSeEAjax()){
        $erros['erro'] = "Acesso negado";
        echo json_encode($erros);
    }else{
        
        $erros[] = "Acesso negado";
        $_SESSION['erros'] = $erros;
        header("location:../visao/index.php");
    }
}

?>