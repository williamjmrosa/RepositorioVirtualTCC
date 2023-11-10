<?php
session_start();
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

            if(!isset($_POST['rg'])) {
                $erros[] = 'Campo RG não existe!';
            } elseif ($_POST['rg'] == "") {
                $erros[] = 'Campo RG em branco!';
            } else {
                $rg = filter_var($_POST['rg'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarRG($rg)){
                    $erros[] = 'RG inválido!';
                }
            }

            if(!isset($_POST['cpf'])) {
                $erros[] = 'Campo CPF não existe!';
            } elseif ($_POST['cpf'] == "") {
                $erros[] = 'Campo CPF em branco!';
            } else {
                if(!Validacao::validarCPF($_POST['cpf'])){
                    $erros[] = 'CPF inválido!';
                }else{
                    $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }

            if(!isset($_POST['telefone'])) {
                $erros[] = 'Campo Telefone não existe!';
            } elseif ($_POST['telefone'] == "") {
                $erros[] = 'Campo Telefone em branco!';
            } else {
                $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);
                if(!Validacao::validarContato($telefone)){
                    $erros[] = 'Telefone inválido!';
                }
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

            if(!isset($_POST['rua'])) {
                $erros[] = 'Campo rua não existe!';
            } elseif ($_POST['rua'] == "") {
                $erros[] = 'Campo rua em branco!';
            } else {
                $rua = filter_var($_POST['rua'], FILTER_SANITIZE_SPECIAL_CHARS);
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

            if(!isset($_POST['campus'])){
                $erros[] = 'Campo Campus não existe!';
            }elseif($_POST['campus'] == "0"){
                $erros[] = 'Nenhum Campus selecionado!';
            }else{
                $campus = filter_var($_POST['campus'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['curso'])){
                $erros[] = 'Campo Curso não existe!';
            }elseif($_POST['curso'] == "0"){
                $erros[] = 'Nenhum Curso selecionado!';
            }else{
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(count($erros) == 0){
                $aluno = new Aluno();
                $aluno->nome = Padronizacao::padronizarNome($nome);
                $aluno->cpf = Padronizacao::padronizarCPF_RG($cpf);
                $aluno->rg = Padronizacao::padronizarCPF_RG($rg);
                $aluno->senha = Seguranca::criptografar($senha);
                $aluno->email = Padronizacao::padronizarEmail($email);
                $aluno->telefone = Padronizacao::padronizarContato($telefone);
                $aluno->end = new Endereco();
                $aluno->end->logradouro = Padronizacao::padronizarNome($rua) . ', ' . $numero;
                $aluno->end->bairro = Padronizacao::padronizarNome($bairro);
                $aluno->end->cidade = Padronizacao::padronizarNome($cidade);
                $aluno->end->uf = Padronizacao::padronizarMaiusculas($uf);
                $aluno->end->cep = Padronizacao::padronizarCEP($cep);
                $aluno->end->complemento = $complemento;
                $aluno->campus = $campus;
                $aluno->curso = $curso;

                $alunoDAO = new AlunoDAO();
                $alunoDAO->cadastrarAluno($aluno);
                
                $_SESSION['msg'] = 'Aluno cadastrado com sucesso!';
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroAluno.php');

            break;
        //Alterar Aluno
        case 2:
            $erros = array();

            if(!isset($_POST['matricula'])){
                $erros[] = 'Campo Matrícula não existe!';
            }elseif($_POST['matricula'] == ""){
                $erros[] = 'Campo Matrícula em branco!';
            }else{
                $matricula = filter_var($_POST['matricula'],FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['idEndereco'])){
                $erros[] = 'Campo Endereço não existe!';
            }elseif($_POST['idEndereco'] == ""){
                $erros[] = 'Campo Endereço em branco!';
            }else{
                $idEndereco = filter_var($_POST['idEndereco'],FILTER_SANITIZE_NUMBER_INT);
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

            if(!isset($_POST['email'])){
                $erros[] = 'Campo E-mail não existe!';
            }elseif($_POST['email'] == ""){
                $erros[] = 'Campo E-mail em branco!';
            }else{
                $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
                if(!Validacao::validarEmail($email)){
                    $erros[] = 'E-mail inválido!';
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

            if(!isset($_POST['rg'])) {
                $erros[] = 'Campo RG não existe!';
            } elseif ($_POST['rg'] == "") {
                $erros[] = 'Campo RG em branco!';
            } else {
                $rg = filter_var($_POST['rg'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarRG($rg)){
                    $erros[] = 'RG inválido!';
                }
            }

            if(!isset($_POST['cpf'])) {
                $erros[] = 'Campo CPF não existe!';
            } elseif ($_POST['cpf'] == "") {
                $erros[] = 'Campo CPF em branco!';
            } else {
                if(!Validacao::validarCPF($_POST['cpf'])){
                    $erros[] = 'CPF inválido!';
                }else{
                    $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }

            if(!isset($_POST['telefone'])) {
                $erros[] = 'Campo Telefone não existe!';
            } elseif ($_POST['telefone'] == "") {
                $erros[] = 'Campo Telefone em branco!';
            } else {
                $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);
                if(!Validacao::validarContato($telefone)){
                    $erros[] = 'Telefone inválido!';
                }
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

            if(!isset($_POST['rua'])) {
                $erros[] = 'Campo rua não existe!';
            } elseif ($_POST['rua'] == "") {
                $erros[] = 'Campo rua em branco!';
            } else {
                $rua = filter_var($_POST['rua'], FILTER_SANITIZE_SPECIAL_CHARS);
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

            if(!isset($_POST['campus'])){
                $erros[] = 'Campo Campus não existe!';
            }elseif($_POST['campus'] == "0"){
                $erros[] = 'Nenhum Campus selecionado!';
            }else{
                $campus = filter_var($_POST['campus'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['curso'])){
                $erros[] = 'Campo Curso não existe!';
            }elseif($_POST['curso'] == "0"){
                $erros[] = 'Nenhum Curso selecionado!';
            }else{
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(count($erros) == 0){
                $aluno = new Aluno();
                $aluno->matricula = $matricula;
                $aluno->nome = Padronizacao::padronizarNome($nome);
                $aluno->cpf = Padronizacao::padronizarCPF_RG($cpf);
                $aluno->rg = Padronizacao::padronizarCPF_RG($rg);
                if(!empty($senha)){
                    $aluno->senha = Seguranca::criptografar($senha);
                }else{
                    $aluno->senha = "";
                }
                $aluno->email = Padronizacao::padronizarEmail($email);
                $aluno->telefone = Padronizacao::padronizarContato($telefone);
                $aluno->end = new Endereco();
                $aluno->end->idEndereco = $idEndereco;
                $aluno->end->logradouro = Padronizacao::padronizarNome($rua) . ', ' . $numero;
                $aluno->end->bairro = Padronizacao::padronizarNome($bairro);
                $aluno->end->cidade = Padronizacao::padronizarNome($cidade);
                $aluno->end->uf = Padronizacao::padronizarMaiusculas($uf);
                $aluno->end->cep = Padronizacao::padronizarCEP($cep);
                $aluno->end->complemento = $complemento;
                $aluno->campus = $campus;
                $aluno->curso = $curso;

                $alunoDAO = new AlunoDAO();
                $alunoDAO->alterarAlunoADM($aluno);
                
                $_SESSION['msg'] = 'Aluno cadastrado com sucesso!';
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroAluno.php');
            

            break;
        //Desativar/Ativar Aluno
        case 3:
            $erros = array();
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $alunoDAO = new AlunoDAO();

                $alunos = $alunoDAO->alterarStatusAluno($id);

                if($alunos){
                    $_SESSION['msg'] = "Status Aluno alterado com sucesso.";
                }else{
                    $erros[] = "Erro ao alterar o Status do Aluno.";
                    $_SESSION['erros'] = serialize($erros);
                }

            }else{
                $erros[] = "Acesso negado.";
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroAluno.php');

            break;
        default:
            header('Location: ../visao/telaCadastroAluno.php');
        break;
    }

}
?>