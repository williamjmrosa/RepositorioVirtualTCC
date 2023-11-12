<?php
session_start();
include_once '../dao/visitantedao.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../util/padronizacao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);
    switch($OP){
        //Cadastrar Visitante
        case 1:
            $erros = array();
            
            if(!isset($_POST['nome'])){
                $erros[] = 'Campo Nome Completo não existe!';
            }elseif($_POST['nome'] == ""){
                $erros[] = 'Campo Nome Completo em branco!';
            }else{
                $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarNome($nome)){
                    $erros[] = 'Nome inválido!';
                }
            }

            if(!isset($_POST['email'])){
                $erros[] = 'Campo E-mail não existe!';
            }elseif($_POST['email'] == ""){
                $erros[] = 'Campo E-mail em branco!';
            }else{
                $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
                if(!Validacao::validarEmail($email)){
                    $erros[] = 'E-mail inválido!';
                }elseif(VisitanteDAO::verificarEmail($email)){
                    $erros[] = 'E-mail já cadastrado!';
                }
            }

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }elseif($_POST['senha'] == ""){
                $erros[] = 'Campo Senha em branco!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarSenha($senha)){
                    $erros[] = 'Senha inválida!';
                }
            }

            if(count($erros) == 0){
                $visitante = new Visitante();
                $visitante->nome = Padronizacao::padronizarNome($nome);
                $visitante->email = Padronizacao::padronizarEmail($email);
                $visitante->senha = Seguranca::criptografar($senha);
                
                $visitanteDao = new VisitanteDao();
                $visitanteDao->cadastrarVisitante($visitante);

                $_SESSION['msg'] = "Visitante Cadastrado com sucesso!";
                
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroVisitante.php');

            break;

        // Alterar Visitante
        case 2:

            $erros = array();

            if(!isset($_POST['id']) || empty($_POST['id'])){
                $erros[] = 'Campo ID não existe!';
            }elseif($_POST['id'] == ""){
                $erros[] = 'Campo ID em branco!';
            }else{
                $id = filter_var($_POST['id'],FILTER_SANITIZE_EMAIL);
                if(!isset($_POST['email'])){
                    $erros[] = 'Campo E-mail não existe!';
                }elseif($_POST['email'] == ""){
                    $erros[] = 'Campo E-mail em branco!';
                }else{
                    $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
                    if(!Validacao::validarEmail($email)){
                        $erros[] = 'E-mail inválido!';
                    }elseif(VisitanteDAO::verificarEmail($email) && $email != $id){
                        $erros[] = 'E-mail já cadastrado!';
                    }
                }
            }

            if(!isset($_POST['nome'])){
                $erros[] = 'Campo Nome Completo não existe!';
            }elseif($_POST['nome'] == ""){
                $erros[] = 'Campo Nome Completo em branco!';
            }else{
                $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarNome($nome)){
                    $erros[] = 'Nome inválido!';
                }
            }

            if(count($erros) == 0){
                
                $visitante = new Visitante();
                $visitante->nome = Padronizacao::padronizarNome($nome);
                $visitante->email = Padronizacao::padronizarEmail($email);
                $visitanteDao = new VisitanteDao();
                
                if($visitanteDao->alterarVisitante($visitante, $id)){
                    $_SESSION['msg'] = "Visitante alterado com sucesso!";
                }else{
                    $erros[] = 'Erro ao alterar Visitante!';
                    $_SESSION['erros'] = serialize($erros);
                }
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroVisitante.php');
            
            break;

        // Excluir Visitante
        case 3:
            $erros = array();

            if(isset($_GET['id']) && !empty($_GET['id'])){
                
                $id = filter_var($_GET['id'],FILTER_SANITIZE_EMAIL);
                $visitanteDao = new VisitanteDao();
                if($visitanteDao->excluirVisitante($id)){
                    $_SESSION['msg'] = "Visitante excluído com sucesso!";
                }else{
                    $erros[] = 'Erro ao excluir Visitante!';
                    $_SESSION['erros'] = serialize($erros);
                }

            }else{
                $erros[] = "Acesso negado!";
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroVisitante.php');
            break;
        default:
            header('Location: ../visao/telaCadastroVisitante.php');
            break;
    }

} else {
    header('Location: ../visao/telaCadastroVisitante.php');
} 

?>