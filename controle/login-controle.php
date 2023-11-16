<?php
session_start();
include_once '../dao/alunodao.class.php';
include_once '../dao/professordao.class.php';
include_once '../dao/visitantedao.class.php';
include_once '../dao/bibliotecariodao.class.php';
include_once '../dao/admdao.class.php';
include_once '../dao/enderecodao.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';
include_once '../Modelo/endereco.class.php';
include_once '../util/seguranca.class.php';

$email = "";
$senha = "";
$tipoAcesso = "";

function entradas() {
    global $email, $senha;
    if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $senha = filter_var($_POST['senha'], FILTER_SANITIZE_SPECIAL_CHARS);
    
        return true;    
    }else{
        return false;
    }
}

function login() {
    global $email, $senha, $tipoAcesso;
    switch($tipoAcesso){
        case 1:
            if(entradas() == true){

                $alunoDAO = new AlunoDAO();
                $aluno = $alunoDAO->fazerLogin($email,Seguranca::criptografar($senha));

                if($aluno != null){
                    $_SESSION['usuario'] = serialize($aluno);
                    $_SESSION['msg'] = "Login efetuado com sucesso!";
                    header('Location: ../visao/index.php');
                    break;
                }else{
                    $_SESSION['erro'] = "Email ou senha inválidos!";
                }
            }else{
                $_SESSION['erro'] = "Preencha todos os campos!";
            }

            header('Location: ../visao/telaLogin.php');

            break;
        case 2:

            if(entradas() == true){

                $professorDAO = new ProfessorDAO();
                $professor = $professorDAO->fazerLogin($email,Seguranca::criptografar($senha));
                
                if($professor != null){
                    $_SESSION['usuario'] = serialize($professor);
                    $_SESSION['msg'] = "Login efetuado com sucesso!";
                    header('Location: ../visao/index.php');
                    break;
                }else{
                    $_SESSION['erro'] = "Email ou senha inválidos!";
                }
            }else{
                $_SESSION['erro'] = "Preencha todos os campos!";
            }

            header('Location: ../visao/telaLogin.php');

            break;
        case 3:

            if(entradas() == true){

                $visitanteDAO = new VisitanteDAO();
                $visitante = $visitanteDAO->fazerLogin($email,Seguranca::criptografar($senha));

                if($visitante != null){
                    $_SESSION['usuario'] = serialize($visitante);
                    $_SESSION['msg'] = "Login efetuado com sucesso!";
                    header('Location: ../visao/index.php');
                    break;
                }else{
                    $_SESSION['erro'] = "Email ou senha inválidos!";
                }

            }else{
                $_SESSION['erro'] = "Preencha todos os campos!";
            }

            header('Location: ../visao/telaLogin.php');

            break;

        case 4:
            if(entradas() == true){
                $bibliotecarioDAO = new BibliotecarioDAO();
                $bibliotecario = $bibliotecarioDAO->fazerLogin($email,Seguranca::criptografar($senha));

                if($bibliotecario != null){
                    $_SESSION['usuario'] = serialize($bibliotecario);
                    $_SESSION['msg'] = "Login efetuado com sucesso!";
                    header('Location: ../visao/index.php');
                    break;
                }else{
                    $_SESSION['erro'] = "Email ou senha inválidos!";
                    
                }

            }else{
                $_SESSION['erro'] = "Preencha todos os campos!";
            }

            header('Location: ../visao/telaLogin.php');
            
            break;

        case 5:
            if(entradas() == true){
                $admDAO = new AdmDAO();
                $adm = $admDAO->fazerLogin($email,Seguranca::criptografar($senha));

                if($adm != null){
                    $_SESSION['usuario'] = serialize($adm);
                    $_SESSION['msg'] = "Login efetuado com sucesso!";
                    header('Location: ../visao/index.php');
                    break;
                }else{
                    $_SESSION['erro'] = "Email ou senha inválidos!";
                }
            }else{
                $_SESSION['erro'] = "Preencha todos os campos!";
            }

            header('Location: ../visao/telaLogin.php');

            break;
        // Logout
        case 6:

            if(isset($_SESSION['usuario'])){
                unset($_SESSION['usuario']);
                $_SESSION['msg'] = "Logout efetuado com sucesso!";
            }else{
                $_SESSION['erro'] = "Você não esta logado!";
            }

            header('Location: ../visao/telaLogin.php');

            break;
        default:
            $_SESSION['erro'] = "Opção de acesso inválida!";
            //header('Location: ../visao/telaLogin.php');
            break;

    }
}

if(isset($_GET['OP']) && $_GET['OP'] == 6){
    echo "entrou";
    $tipoAcesso = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);
    login();
}elseif(isset($_POST['tipoAcesso']) && !empty($_POST['tipoAcesso'])) {
    $tipoAcesso = filter_var($_POST['tipoAcesso'], FILTER_SANITIZE_NUMBER_INT);
    login();
}

?>