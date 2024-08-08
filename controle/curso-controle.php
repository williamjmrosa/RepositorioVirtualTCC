<?php
session_start();
include_once '../util/padronizacao.class.php';
include_once '../util/validacao.class.php';
include_once '../Modelo/curso.class.php';
include_once '../dao/cursodao.class.php';

if(isset($_GET['OP'])){

    $OP = filter_var(@$_REQUEST['OP'],FILTER_SANITIZE_NUMBER_INT);

    SWITCH ($OP){
        //Cadastrar Curso
        case 1:
            
            if(!(isset($_POST['nome']) && isset($_POST['ensino']))){
                header('location: ../index.php');
                break;
            }else{
                $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
            }      

            $erro = array();

            if(!Validacao::validarNome($nome)){
                $erro[] = "Nome para Curso invalido!";
            }

            if(!Validacao::validarTamanho($nome,60)){
                $erro[] = "Nome muito grande (max. 60 caracteres)";
            }

            if(!isset($_POST['ensino'])){
                $erro[] = "Ensino para Curso invalido!";
            }else{
                $ensino = filter_var($_POST['ensino'],FILTER_SANITIZE_NUMBER_INT);
            }

            if(!($ensino == 0 || $ensino == 1 || $ensino == 2)){
                $erro[] = "Ensino não Selecionado";
            }
            
            if(count($erro) == 0){    
                
                $c = new Curso;
                $c->nome = Padronizacao::padronizarNome($nome);
                $c->ensino = $ensino;
                
                $cDAO = new CursoDAO;
                $erroBanco = $cDAO->cadastrarCurso($c);
                
                if(isset($erroBanco)){
                    $erro[] = $erroBanco."\n";
                    $_SESSION['erros'] = $erro;
                    
                }else{
                    $_SESSION['msg'] = 'Curso Cadastrado!\n';
                }
            }else{
                $_SESSION['erros'] = serialize($erro);
            }


            header('location: ../visao/telaCadastroCurso.php');

        break;

        // Alterar Curso
        case 2:
            $erro = array();

            if(!isset($_POST['idCurso'])){
                $erro[] = "ID para Curso não existe!";
            }elseif($_POST['idCurso'] == ""){
                $erro[] = "ID para Curso em branco!";
            }else{
                $idCurso = filter_var($_POST['idCurso'],FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['nome'])){
                $erro[] = "Campo Nome para Curso não existe!";
            }elseif(!Validacao::validarNome($_POST['nome'])){
                $erro[] = "Nome para Curso invalido!";
            }else{
                $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($nome,60)){
                    $erro[] = "Tamanho do nome invalido! (max 60 caracteres)";
                }
            }            

            if(!isset($_POST['ensino'])){
                $erro[] = "Ensino para Curso invalido!";
            }elseif(!($_POST['ensino'] == 0 || $_POST['ensino'] == 1 || $_POST['ensino'] == 2)){
                $erro[] = "Ensino não Selecionado";
            }else{
                $ensino = filter_var($_POST['ensino'],FILTER_SANITIZE_NUMBER_INT);
            }   
            
            if(count($erro) == 0){    
                
                $c = new Curso();
                $c->idCurso = $idCurso;
                $c->nome = Padronizacao::padronizarNome($nome);
                $c->ensino = $ensino;
                
                $cDAO = new CursoDAO();
                $erroBanco = $cDAO->alterarCurso($c);
                
                if(!$erroBanco){
                    $erro[] = $erroBanco."\n";
                    $_SESSION['erros'] = serialize($erro);
                    
                }else{
                    $_SESSION['msg'] = 'Curso Alterado!';
                }
            }else{
                $_SESSION['erros'] = serialize($erro);
            }


            header('location: ../visao/telaCadastroCurso.php');

            break;
        // Desativar/Ativar Curso
        case 3:
            $erro = array();

            if(!isset($_GET['id'])){
                $erro[] = "ID para Curso não existe!";
            }elseif($_GET['id'] == ""){
                $erro[] = "ID para Curso em branco!";
            }else{
                $idCurso = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
            }

            if(count($erro) == 0){
                $cDAO = new CursoDAO;
                $erroBanco = $cDAO->alterarStatusCurso($idCurso);

                if(!$erroBanco == true){
                    $erro[] = $erroBanco."\n";
                    $_SESSION['erros'] = serialize($erro);
                }else{
                    $_SESSION['msg'] = 'Status do Curso alterado!\n';
                }
            }else{
                $_SESSION['erros'] = serialize($erro);
            }

            header('location: ../visao/telaCadastroCurso.php');
            
            break;
        default:
            $erro[] = "Nenhuma opcao valida selecionada!";
            $_SESSION['erros'] = serialize($erro);
            header('location: ../visao/telaCadastroCurso.php');
            break;

    }

}else{
    $erro[] = "Nenhuma opcao selecionada!";
    $_SESSION['erros'] = serialize($erro);
    header('location: ../visao/telaCadastroCurso.php');
}

?>