<?php
include_once '../dao/alunodao.class.php';
include_once '../dao/enderecodao.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/endereco.class.php';
include_once '../util/padronizacao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';

if(isset($_GET['OP'])){

    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch($OP){
        //Cadastrar Aluno
        case 1:
            $erros = array();

            if(!isset($_POST['nome'])){
                $erros[] = 'Campo Nome Completo não existe!';
            }elseif($_POST['nome'] == ""){
                $erros[] = 'Campo Nome Completo em branco!';
            }else{
                $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['email'])){
                $erros[] = 'Campo E-mail não existe!';
            }elseif($_POST['email'] == ""){
                $erros[] = 'Campo E-mail em branco!';
            }else{
                $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
            }

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }elseif($_POST['senha'] == ""){
                $erros[] = 'Campo Senha em branco!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['rg'])) {
                $erros[] = 'Campo RG não existe!';
            } elseif ($_POST['rg'] == "") {
                $erros[] = 'Campo RG em branco!';
            } else {
                $rg = filter_var($_POST['rg'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['cpf'])) {
                $erros[] = 'Campo CPF não existe!';
            } elseif ($_POST['cpf'] == "") {
                $erros[] = 'Campo CPF em branco!';
            } else {
                $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['telefone'])) {
                $erros[] = 'Campo Telefone não existe!';
            } elseif ($_POST['telefone'] == "") {
                $erros[] = 'Campo Telefone em branco!';
            } else {
                $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['bairro'])) {
                $erros[] = 'Campo Bairro não existe!';
            } elseif ($_POST['bairro'] == "") {
                $erros[] = 'Campo Bairro em branco!';
            } else {
                $bairro = filter_var($_POST['bairro'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['cidade'])) {
                $erros[] = 'Campo Cidade não existe!';
            } elseif ($_POST['cidade'] == "") {
                $erros[] = 'Campo Cidade em branco!';
            } else {
                $cidade = filter_var($_POST['cidade'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['uf'])) {
                $erros[] = 'Campo UF não existe!';
            } elseif ($_POST['uf'] == "") {
                $erros[] = 'Campo UF em branco!';
            } else {
                $uf = filter_var($_POST['uf'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['logradouro'])) {
                $erros[] = 'Campo Logradouro não existe!';
            } elseif ($_POST['logradouro'] == "") {
                $erros[] = 'Campo Logradouro em branco!';
            } else {
                $logradouro = filter_var($_POST['logradouro'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['cep'])) {
                $erros[] = 'Campo CEP não existe!';
            } elseif ($_POST['cep'] == "") {
                $erros[] = 'Campo CEP em branco!';
            } else {
                $cep = filter_var($_POST['cep'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['complemento'])) {
                $erros[] = 'Campo Complemento não existe!';
            } elseif ($_POST['complemento'] == "") {
                $erros[] = 'Campo Complemento em branco!';
            } else {
                $complemento = filter_var($_POST['complemento'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['numero'])) {
                $erros[] = 'Campo Numero não existe!';
            } elseif ($_POST['numero'] == "") {
                $erros[] = 'Campo Numero em branco!';
            } else {
                $numero = filter_var($_POST['numero'], FILTER_SANITIZE_NUMBER_INT);
            }

            

            break;
        case 2:
            break;
        default:
        break;
    }

}
?>