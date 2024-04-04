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

            if(count($erro) == 0){
                $iDAO = new IndicacaoDAO();
                if($iDAO->cadastrarIndicacao($idTCC, $idUsuario, $instituicao, $curso)){
                    echo json_encode("Indicado com sucesso!");
                }else{
                    echo json_encode("Erro ao Indicar");
                }
            }else{
                if(verSeEAjax()){
                    $resposta['erros'] = $erro;
                    echo json_encode($resposta);
                }else{
                    $_SESSION['erros'] = $erro;
                    header("location:../visao/index.php");
                }
            }
    }
}else{
    if(verSeEAjax()){
        echo json_encode("Erro ao Indicar");
    }else{
        $erros = array();
        $erros[] = "Erro ao Indicar";
        $_SESSION['erros'] = $erros;
        header("location:../visao/index.php");
    }
}

?>