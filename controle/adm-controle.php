<?php
session_start();
include_once '../dao/admdao.class.php';
include_once '../Modelo/adm.class.php';
include_once '../util/padronizacao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';

if(isset($_GET['OP'])){

    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch($OP){
        //Cadastrar Administrador
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
                }elseif(AdmDAO::verificarEmail($email)){
                    $erros[] = 'E-mail já existe!';
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
                $adm = new Adm();
                $adm->nome = Padronizacao::padronizarNome($nome);
                $adm->email = Padronizacao::padronizarEmail($email);
                $adm->senha = Seguranca::criptografar($senha);

                $admDAO = new AdmDAO();

                if($admDAO->cadastrarAdministrador($adm)){
                    $_SESSION['msg'] = "Administrador Cadastrado com Sucesso!";
                }else{
                    $erros[] = "Erro ao cadastrar Administrador";
                    $_SESSION['erros'] = serialize($erros);
                }          

            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroAdm.php');

            break;
        //Alterar Administrador
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
                    }elseif(admDAO::verificarEmail($email) && $email != $id){
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

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarSenha($senha) && $senha != ""){
                    $erros[] = 'Senha inválida!';
                }
            }

            if(count($erros) == 0){
                
                $adm = new Adm();
                $adm->nome = Padronizacao::padronizarNome($nome);
                $adm->email = Padronizacao::padronizarEmail($email);
                $adm->senha = $senha == "" ? $senha : Seguranca::criptografar($senha);
                $admDAO = new AdmDAO();
                
                if($admDAO->alterarAdministrador($adm, $id)){
                    $_SESSION['msg'] = "Administrador alterado com sucesso!";
                }else{
                    $erros[] = 'Erro ao alterar Administrador!';
                    $_SESSION['erros'] = serialize($erros);
                }
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroAdm.php');

            break;
        //Excluir Administrador
        case 3:
            $erros = array();

            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = filter_var($_GET['id'],FILTER_SANITIZE_EMAIL);
                $admDAO = new AdmDAO();
                if($admDAO->excluirAdm($id)){
                    $_SESSION['msg'] = "Administrador excluído com sucesso!";
                }else{
                    $erros[] = 'Erro ao excluir Administrador!';
                    $_SESSION['erros'] = serialize($erros);
                }
            }

        default:
            header('Location: ../visao/telaCadastroAdm.php');
        break;
    }

}else{
    $_SESSION['erro'] = "Acesso não autorizado!";
    header('Location: ../visao/index.php');
}

?>