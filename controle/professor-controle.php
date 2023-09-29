<?php
session_start();
include_once '../dao/professordao.class.php';
include_once '../Modelo/professor.class.php';
include_once '../util/padronizacao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';
include_once '../dao/enderecodao.class.php';

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);
    switch($OP){
        //Cadastrar Professor
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
                if(!Validacao::validarSenha($senha)){
                    $erros[] = 'Senha inválida!';
                }
            }

            if(!isset($_POST['cpf'])) {
                $erros[] = 'Campo CPF não existe!';
            }elseif($_POST['cpf'] == "") {
                $erros[] = 'Campo CPF em branco!';
            }else{
                $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarCPF($cpf)){
                    $erros[] = 'CPF inválido!';
                }
            }

            if(!isset($_POST['rg'])) {
                $erros[] = 'Campo RG não existe!';
            }elseif($_POST['rg'] == "") {
                $erros[] = 'Campo RG em branco!';
            }else{
                $rg = filter_var($_POST['rg'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarRG($rg)){
                    $erros[] = 'RG inválido!';
                }
            }

            if(!isset($_POST['telefone'])) {
                $erros[] = 'Campo Telefone não existe!';
            }elseif($_POST['telefone'] == "") {
                $erros[] = 'Campo Telefone em branco!';
            }else{
                $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);
                if(!Validacao::validarContato($telefone)){
                    $erros[] = 'Telefone inválido!';
                }
            }

            if(!isset($_POST['cep'])) {
                $erros[] = 'Campo CEP não existe!';
            }elseif($_POST['cep'] == "") {
                $erros[] = 'Campo CEP em branco!';
            }else{
                $cep = filter_var($_POST['cep'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['bairro'])) {
                $erros[] = 'Campo Bairro não existe!';
            }elseif($_POST['bairro'] == "") {
                $erros[] = 'Campo Bairro em branco!';
            }else{
                $bairro = filter_var($_POST['bairro'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['cidade'])) {
                $erros[] = 'Campo Cidade não existe!';
            }elseif($_POST['cidade'] == "") {
                $erros[] = 'Campo Cidade em branco!';
            }else{
                $cidade = filter_var($_POST['cidade'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['uf'])) {
                $erros[] = 'Campo UF não existe!';
            }elseif($_POST['uf'] == "") {
                $erros[] = 'Campo UF em branco!';
            }else{
                $uf = filter_var($_POST['uf'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['rua'])) {
                $erros[] = 'Campo Rua não existe!';
            }elseif($_POST['rua'] == "") {
                $erros[] = 'Campo Rua em branco!';
            }else{
                $rua = filter_var($_POST['rua'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['numero'])) {
                $erros[] = 'Campo número não existe!';
            }elseif($_POST['numero'] == "") {
                $erros[] = 'Campo número em branco!';
            }else{
                $numero = filter_var($_POST['numero'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['complemento'])) {
                $erros[] = 'Campo Complemento não existe!';
            }else{
                $complemento = filter_var($_POST['complemento'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(count($erros) == 0){
                $professor = new Professor();
                $professor->nome = Padronizacao::padronizarNome($nome);
                $professor->email = Padronizacao::padronizarEmail($email);
                $professor->senha = Seguranca::criptografar($senha);
                $professor->cpf = Padronizacao::padronizarCPF_RG($cpf);
                $professor->rg = Padronizacao::padronizarCPF_RG($rg);
                $professor->telefone = Padronizacao::padronizarContato($telefone);
                $professor->end = new Endereco();
                $professor->end->logradouro = Padronizacao::padronizarNome($rua) . ', ' . $numero;
                $professor->end->bairro = Padronizacao::padronizarNome($bairro);
                $professor->end->cidade = Padronizacao::padronizarNome($cidade);
                $professor->end->uf = Padronizacao::padronizarMaiusculas($uf);
                $professor->end->cep = Padronizacao::padronizarCEP($cep);
                $professor->end->complemento = $complemento;

                $professorDao = new ProfessorDao();
                $professorDao->cadastrarProfessor($professor);

                $_SESSION['msg'] = 'Professor cadastrado com sucesso!';
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroProfessor.php');

            break;
        default:
        break;
    }
}else{
    header('Location: ../visao/telaCadastroProfessor.php');
}

?>