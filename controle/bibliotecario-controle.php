<?php
session_start();
include_once '../dao/bibliotecariodao.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../util/padronizacao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';

if(isset($_GET['OP']) && isset($_SESSION['usuario'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    switch($OP){
        //Cadastrar Bibliotecario
        case 1:
            $erros = array();
            
            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

                if(!isset($_POST['nome'])){
                    $erros[] = 'Campo Nome Completo não existe!';
                }elseif($_POST['nome'] == ""){
                    $erros[] = 'Campo Nome Completo em branco!';
                }else{
                    $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!Validacao::validarNome($nome)){
                        $erros[] = 'Nome inválido!';
                    }
                    if(!Validacao::validarTamanho($nome,60)){
                        $erros[] = 'Nome muito grande! (max. 60 caracteres)';
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
                    }elseif(BibliotecarioDAO::verificarEmail($email)){
                        $erros[] = 'E-mail já existe!';
                    }
                    if(!Validacao::validarTamanho($email,60)){
                        $erros[] = 'E-mail muito grande! (max. 60 caracteres)';
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

                    $bibliotecario = new Bibliotecario();
                    $bibliotecario->nome = Padronizacao::padronizarNome($nome);
                    $bibliotecario->email = Padronizacao::padronizarEmail($email);
                    $bibliotecario->senha = Seguranca::criptografar($senha);

                    $bibliotecaDAO = new BibliotecarioDAO();
                    $bibliotecaDAO->cadastrarBibliotecario($bibliotecario);

                    $_SESSION['msg'] = 'Cadastro realizado com sucesso!';

                }else{
                    $_SESSION['erros'] = serialize($erros);
                }

                header('Location: ../visao/telaCadastroBibliotecario.php');

            }
            
            break;
        
        //Alterar Bibliotecario
        case 2:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

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
                        }elseif(BibliotecarioDAO::verificarEmail($email) && $email != $id){
                            $erros[] = 'E-mail já cadastrado!';
                        }
                        if(!Validacao::validarTamanho($email,60)){
                            $erros[] = 'E-mail muito grande! (max. 60 caracteres)';
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
                    if(!Validacao::validarTamanho($nome,60)){
                        $erros[] = 'Nome muito grande! (max. 60 caracteres)';
                    }
                }

                if(!isset($_POST['senha'])){
                    $erros[] = 'Campo Senha não existe!';
                }else{
                    $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!Validacao::validarSenha($senha) && $senha != ""){
                        $erros[] = 'Senha inválida!';
                    }
                }

                if(count($erros) == 0){
                    
                    $bibliotecario = new Bibliotecario();
                    $bibliotecario->nome = Padronizacao::padronizarNome($nome);
                    $bibliotecario->email = Padronizacao::padronizarEmail($email);
                    $bibliotecario->senha = $senha == "" ? $senha : Seguranca::criptografar($senha);
                    $bibliotecarioDAO = new BibliotecarioDAO();
                    
                    if($bibliotecarioDAO->alterarbibliotecario($bibliotecario, $id)){
                        $_SESSION['msg'] = "Bibliotecário alterado com sucesso!";
                    }else{
                        $erros[] = 'Erro ao alterar Bibliotecário!';
                        $_SESSION['erros'] = serialize($erros);
                    }
                }else{
                    $_SESSION['erros'] = serialize($erros);
                }

                header('Location: ../visao/telaCadastroBibliotecario.php');
            }

            break;

        //Excluir Bibliotecario
        case 3:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

                if(isset($_GET['id']) && !empty($_GET['id'])){
                    $id = filter_var($_GET['id'],FILTER_SANITIZE_EMAIL);
                    $bibliotecarioDAO = new BibliotecarioDAO();
                    if($bibliotecarioDAO->excluirBibliotecario($id)){
                        $_SESSION['msg'] = "Bibliotecário excluído com sucesso!";
                    }else{
                        $erros[] = 'Erro ao excluir Bibliotecário!';
                        $_SESSION['erros'] = serialize($erros);
                    }
                }else{
                    $erros[] = 'Acesso não autorizado!';
                    $_SESSION['erros'] = serialize($erros);
                }

                header('Location: ../visao/telaCadastroBibliotecario.php');
            }

            break;
        
        default:
            $erros = array();
            $erros[] = 'Acesso invalido!';
            $_SESSION['erros'] = serialize($erros);
            header('Location: ../visao/telaCadastroBibliotecario.php');
        break;
    }

}else{
    $erros = array();
    $erros[] = 'Acesso não autorizado!';
    $_SESSION['erros'] = serialize($erros);
    header('Location: ../visao/telaCadastroBibliotecario.php');
}

?>