<?php
include_once '../Modelo/adm.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/visitante.class.php';
session_start();
if(isset($_SESSION['usuario'])) {
  $user = unserialize($_SESSION['usuario']);
  $tipo = get_class($user);
  
  if($tipo != 'Adm'){
    $erros[] = "Efetue o login como Adm para acessar tela de Cadastro Adm!";
    $_SESSION['erros'] = $erros;
    header('location:../index.php');
  }

}else{
  $erros[] = "Efetue o login para acessar o sistema!";
  $_SESSION['erros'] = $erros;
  header('location:../index.php');
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/cadastro-usuario.css">
  <link rel="icon" type="image/jpg" href="../img/icone.png" />
</head>

<body class="container-fluid m-0 p-0 fundo-secundario">
  <div class="fundo-primario">
    <div class="mb-2">
      <nav class="p-2 row" id="menu">
        <div class="col-6">
          <h3 class="home">TCC AQUI</h3>
          <a class="btn fundo-secundario fw-bold" href="index.php">Home</a>
          <a class="btn fundo-secundario fw-bold" href="../visao/contatos.php">Contatos</a>
          <a class="btn fundo-secundario fw-bold" href="../visao/tccMarcado.php">Marcados</a>
        </div>
        <div class="div-login col-6">
          <ul id="login">
            <!-- Menu de Login -->
            <!-- Carregado via js/jquery -->
          </ul>
        </div>
      </nav>
    </div>

  </div>
  <div class="corpo">
    <div class="cadastro w-auto">
      <form class="row g-3" method="POST" action="../controle/adm-controle.php?OP=1">
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
          <h3>Cadastrar Administrador</h3>
        </div>
        <div class="col-12">
          <label for="nome" class="form-label">Nome Completo</label>
          <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
        </div>
        <div class="col-lg-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
        </div>
        <div class="col-lg-6">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" maxlength="20">
          <i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha(this)"></i>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
      </form>
    </div>
    <div class="row g-3 m-4 cadastro w-auto">
      <div class="col-12">
        <h3>Administradores Cadastrados</h3>
      </div>
      <div class="col-12 row g-3">
        <div class="col-2">
          <select class="form-select" name="busca" id="busca">
            <option value="nome">Nome</option>
            <option value="email">Email</option>
          </select>
        </div>
        <div class="col-6">
          <input type="text" class="form-control" id="buscarNome" name="buscarNome" placeholder="Buscar Administrador">
        </div>
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">E-mail</th>
            <th scope="col">Nome</th>
            <th class="text-center" scope="col">Alterar</th>
            <th class="text-center" scope="col">Status</th>
          </tr>
        </thead>
        <tbody id="alterar">
          <!-- Inicio da Lista de Adm para Alterar/Excluir -->
          <!-- Carregamento da Lista de Adm via JS -->
          <!-- Fim da Lista de Adm para Alterar/Excluir -->
        </tbody>
      </table>
    </div>
  </div>
  <script src="../Framework/js/jquery-3.6.4.js"></script>
  <script src="../Framework/js/popper.min.js"></script>
  <script src="../Framework/js/bootstrap.js"></script>
  <script src="../js/js-cadastro-usuarios.js"></script>
  <script src="../js/js-cadastro-adm.js"></script>
  <script src="../Framework/jQuery-Mask-Plugin/jquery.mask.js"></script>
</body>

</html>