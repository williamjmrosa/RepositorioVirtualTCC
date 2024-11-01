<?php
session_start();
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';
include_once '../Modelo/endereco.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/curso.class.php';
include_once '../dao/alunodao.class.php';
include_once '../dao/professordao.class.php';
include_once '../dao/visitantedao.class.php';
include_once '../dao/bibliotecariodao.class.php';
include_once '../dao/admdao.class.php';
include_once '../dao/enderecodao.class.php';
include_once '../util/seguranca.class.php';
include_once '../util/validacao.class.php';
include_once '../util/padronizacao.class.php';

if(isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    switch($tipo){
        case 'Aluno':
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
                if(AlunoDAO::verificarEmail($email) && $email != $user->email){
                    $erros[] = 'E-mail já cadastrado!';
                }
            }

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarSenha($senha) && $senha != ""){
                    $erros[] = 'Senha inválida! (Min 6 caracteres Max 20, deve conter, uma letra maiúscula, uma letra minuscula, um número e um caractere especial)';
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

            
            if(count($erros) == 0){
                $aluno = new Aluno();
                $aluno->matricula = $matricula;
                $aluno->nome = Padronizacao::padronizarNome($nome);
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

                $alunoDAO = new AlunoDAO();
                $alunoDAO->alterarAlunoADM($aluno);
                
                $_SESSION['msg'] = 'Dados do Aluno: ' . $aluno->nome . ' alterados com sucesso!';
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/perfil.php');

            break;
        case 'Professor':

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
                if(ProfessorDAO::verificarEmail($email) && $email != $user->email){
                    $erros[] = 'E-mail já cadastrado!';
                }
            }

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarSenha($senha) && $senha != ""){
                    $erros[] = 'Senha inválida! (Min 6 caracteres Max 20, deve conter, uma letra maiúscula, uma letra minuscula, um número e um caractere especial)';
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

            if(count($erros) == 0){
                $professor = new Professor();
                $professor->matricula = $matricula;
                $professor->nome = Padronizacao::padronizarNome($nome);
                $professor->email = Padronizacao::padronizarEmail($email);
                if(!empty($senha)){
                    $professor->senha = Seguranca::criptografar($senha);
                }else{
                    $professor->senha = "";
                }
                $professor->telefone = Padronizacao::padronizarContato($telefone);
                $professor->end = new Endereco();
                $professor->end->idEndereco = $idEndereco;
                $professor->end->logradouro = Padronizacao::padronizarNome($rua) . ', ' . $numero;
                $professor->end->bairro = Padronizacao::padronizarNome($bairro);
                $professor->end->cidade = Padronizacao::padronizarNome($cidade);
                $professor->end->uf = Padronizacao::padronizarMaiusculas($uf);
                $professor->end->cep = Padronizacao::padronizarCEP($cep);
                $professor->end->complemento = $complemento;

                $professorDAO = new ProfessorDAO();

                if($professorDAO->alterarProfessorADM($professor)){
                    $_SESSION['msg'] = 'Dados do Professor(a): ' . $professor->nome . ' alterado com sucesso!';
                }else{
                    $erros[] = 'Erro ao alterar professor!';
                    $_SESSION['erros'] = serialize($erros);
                }
                
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/perfil.php');

            break;
        case 'Visitante':

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

            if(!isset($_POST['senha'])){
                $erros[] = 'Campo Senha não existe!';
            }else{
                $senha = filter_var($_POST['senha'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarSenha($senha) && $senha != ""){
                    $erros[] = 'Senha inválida! (Min 6 caracteres Max 20, deve conter, uma letra maiúscula, uma letra minuscula, um número e um caractere especial)';
                }
            }

            if(count($erros) == 0){
                
                $visitante = new Visitante();
                $visitante->nome = Padronizacao::padronizarNome($nome);
                $visitante->email = Padronizacao::padronizarEmail($email);
                $visitante->senha = $senha == "" ? $senha : Seguranca::criptografar($senha);
                $visitanteDao = new VisitanteDao();
                
                if($visitanteDao->alterarVisitante($visitante, $id)){
                    $_SESSION['msg'] = "Dados do Visitante: $visitante->nome alterado com sucesso!";
                }else{
                    $erros[] = 'Erro ao alterar Visitante!';
                    $_SESSION['erros'] = serialize($erros);
                }
            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/perfil.php');

            break;
        case 'Bibliotecario':

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
                    }elseif(BibliotecarioDAO::verificarEmail($email) && $email != $id){
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
                    $erros[] = 'Senha inválida! (Min 6 caracteres Max 20, deve conter, uma letra maiúscula, uma letra minuscula, um número e um caractere especial)';
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

            header('Location: ../visao/perfil.php');

            break;

        case 'Adm':

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
                    $erros[] = 'Senha inválida! (Min 6 caracteres Max 20, deve conter, uma letra maiúscula, uma letra minuscula, um número e um caractere especial)';
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

            header('Location: ../visao/perfil.php');
            break;

        default:
            $_SESSION['erro'] = "Tipo de acesso inválido!";
            header('Location: ../visao/telaLogin.php');

        }
}else{
    $_SESSION['erro'] = "Deve estar logado!";
    header('Location: ../visao/telaLogin.php');
}
?>