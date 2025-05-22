<?php
session_start();
include_once '../Modelo/tcc.class.php';
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

function userCompleto($user) {
    $tipo = get_class($user);
    if($tipo == 'Aluno'){
        
        $alunoDAO = new AlunoDAO();
        $aluno = $alunoDAO->buscarAlunoPorMatricula($user->matricula,false);
        
        if($aluno != null){

           return $aluno;
            
        }else{
           return $user;
        }

    }else if($tipo == 'Professor'){
        
        $professorDAO = new ProfessorDAO();
        $professor = $professorDAO->buscarProfessorPorMatricula($user->matricula,false);
        
        if($professor != null){
           return $professor;
            
        }else{
           return $user;
        }

    }else if($tipo == 'Visitante'){
        
        $visitanteDAO = new VisitanteDAO();
        $visitante = $visitanteDAO->encontrarVisitantePorEmail($user->email,false);
        
        if($visitante != null){
           return $visitante;
            
        }else{
           return $user;
        }

    }else if($tipo == 'Bibliotecario'){
        
        $bibliotecarioDAO = new BibliotecarioDAO();
        $bibliotecario = $bibliotecarioDAO->encontrarBibliotecarioPorEmail($user->email,false);
        
        if($bibliotecario != null){
           return $bibliotecario;
            
        }else{
           return $user;
        }

    }else if($tipo == 'Adm'){

        $admDAO = new AdmDAO();
        $adm = $admDAO->encontrarAdmPorEmail($user->email,false);
        
        if($adm != null){
           return $adm;
            
        }else{
           return $user;
        }

    }

}

if (isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    $user = userCompleto($user);

} else {
    $_SESSION['erro'] = "Efetue o login para acessar o sistema!";
    header('Location: ../visao/telaLogin.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>TCC AQUI</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../Framework/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro-usuario.css">
    <link rel="icon" type="image/jpg" href="../img/icone.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body class="container-fluid m-0 p-0">
    <div class="fundo-primario">
        <div class="mb-1">
        <nav class="p-2 d-flex flex-column align-items-start" id="menu">
                <div class="row w-100">    
                    <div class="col-6">
                        <h3 class="home">TCC AQUI</h3>
                        <a class="btn fundo-secundario fw-bold" href="index.php">Home</a>
                        <a class="btn fundo-secundario fw-bold" href="../visao/contatos.php">Contatos</a>
                        <a class="btn fundo-secundario fw-bold" href="../visao/tccMarcado.php">Marcados</a>
                    </div>
                    <div class="div-login col-5 align-items-end">
                        <ul id="login">
                            <!-- Menu de Login -->
                            <!-- Carregado via js/jquery -->
                        </ul>
                    </div>
                </div>
                <div class="row align-items-center w-100">
                    <div class="text-center col-12">
                        <form id="formPesquisar" action="../controle/tcc-controle.php?OP=5" method="post">
                            <select class="form-select d-inline-block w-auto" name="tipo">
                                <option value="titulo">Titulo</option>
                                <option value="autor">Autor</option>
                                <option value="orientador">Orientador</option>
                            </select>
                            <input class="form-control w-50 d-inline-block textoPesquisa" type="text" name="pesquisar" placeholder="Pesquise">
                            <input class="pesquisar form-control d-inline-block w-auto" type="submit" value="&#128270;">
                        </form>
                    </div>
                </div>
            </nav>
        </div>

    </div>
    <div class="fundo-primario">
        <!-- Inicio Conteudo -->
        
            <div class="d-flex flex-column align-items-center h-100">
                <div class="col-6 fundo-secundario rounded-5 p-5 m-4">
                    <h2>Perfil <?php echo $tipo ?></h2>

                    <form class="row g-3" method="POST" action="../controle/perfil-controle.php">
                        <!-- Mensagens de Alerta do retorno do Cadastro -->
                        <?php

                        if (isset($_SESSION['erros'])) {
                            $erros = unserialize($_SESSION['erros']);
                            unset($_SESSION['erros']);
                            $msg = "";
                            foreach ($erros as $erro) {
                                $msg = $msg . '<p class="m-0"> ' . $erro . '</p>';
                            }
                            $sucesso = false;
                        } elseif (isset($_SESSION['msg'])) {
                            $sucesso = true;
                            $msg = $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        }
                        if (isset($sucesso)) {
                        ?>
                            <div class="alert <?php if ($sucesso) {
                                                    echo 'alert-success';
                                                } else {
                                                    echo 'alert-danger';
                                                } ?>" role="alert">
                                <?php
                                echo $msg;
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- Fim da mensagens de Alerta do retorno do Cadastro -->
                        <div class="col-12">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?php echo $user->nome;?>">
                        </div>
                        <?php
                        if($tipo == "Aluno" || $tipo == "Professor"){
                        ?>
                        <div class="col-12">
                            <label for="cpf" class="form-label">Matricula</label>
                            <input type="text" disabled class="form-control w-50" id="matricula" name="matricula" placeholder="Matricula" value="<?php echo $user->matricula;?>">
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="<?php echo $user->email;?>">
                        </div>
                        <div class="col-lg-6">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" maxlength="20">
                            <i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha(this)"></i>
                        </div>
                        <?php if($tipo == "Aluno"){?>
                        <div class="col-lg-6" id="divCampus">
                            <label for="campusNome" class="form-label">Campus</label>
                            <input type="hidden" id="campus" name="campus" value="<?php echo $user->campus->id; ?>">
                            <input type="text" disabled class="form-control" id="campusNome" placeholder="Campus" value="<?php echo $user->campus->nome;?>">
                        </div>
                        <div class="col-lg-6" id="divCurso">
                            <label for="cursoNome" class="form-label">Curso</label>
                            <input type="hidden" id="curso" name="curso" value="<?php echo $user->curso->idCurso; ?>">
                            <input type="text" disabled class="form-control" id="cursoNome" placeholder="Curso" value="<?php echo $user->curso->nome;?>">
                        </div>
                        <?php }
                        if($tipo == "Aluno" || $tipo == "Professor"){?>
                        <div class="col-lg-6">
                            <label for="rg" class="form-label">RG</label>
                            <input type="text" class="form-control" id="rg" name="rg" placeholder="RG" value="<?php echo $user->rg;?>" disabled>
                        </div>
                        <div class="col-lg-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" value="<?php echo $user->cpf;?>" disabled>
                        </div>
                        <div class="col-lg-12">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control w-25" id="telefone" name="telefone" placeholder="Telefone" value="<?php echo $user->telefone;?>">
                        </div>
                        <div class="col-md-2">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" value="<?php echo $user->end->cep;?>">
                        </div>
                        <div class="col-md-6">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="<?php echo $user->end->cidade;?>">
                        </div>
                        <div class="col-md-4">
                            <label for="uf" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="uf" name="uf" placeholder="Estado" value="<?php echo $user->end->uf;?>">
                        </div>
                        <div class="col-md-6">
                            <label for="rua" class="form-label">Rua</label>
                            <input type="text" class="form-control" id="rua" name="rua" placeholder="Rua" value="<?php echo explode(",", $user->end->logradouro)[0];?>">
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" value="<?php echo $user->end->bairro;?>">
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" value="<?php echo explode(",", $user->end->logradouro)[1];?>">
                        </div>
                        <div class="col-lg-12">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Complemento" value="<?php echo $user->end->complemento;?>">
                        </div>
                        <div>
                            <input type="hidden" name="matricula" value="<?php echo $user->matricula; ?>">
                            <input type="hidden" name="idEndereco" value="<?php echo $user->end->idEndereco; ?>">
                        </div>
                        <?php
                        }else{?>
                            <div>
                                <input type="hidden" name="id" value="<?php echo $user->email; ?>">
                            </div>
                        <?php
                        }?>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            
        </div>
        <!-- Fim Conteudo -->
    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../Framework/jQuery-Mask-Plugin/jquery.mask.js"></script>
    <script src="../js/js-cadastro-usuarios.js"></script>
    <script src="../js/js-tela-principal.js"></script>
</body>

</html>