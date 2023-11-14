<?php
session_start();
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
      <nav class="p-2" id="menu">
        <div class="d-inline-block w-50">
          <h3 class="home">TCC AQUI</h3>
          <a class="btn fundo-secundario fw-bold" href="index.html">Home</a>
          <a class="btn fundo-secundario fw-bold" href="#">Contao</a>
          <a class="btn fundo-secundario fw-bold" href="#">TCC</a>

        </div>
        <div class="div-login">
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
      <form class="row g-3" method="POST" action="../controle/visitante-controle.php?OP=1">
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
          }elseif(isset($_SESSION['msg'])) {
            $sucesso = true;
            $msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
            
          }
          if(isset($sucesso)){
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
            <h3>Cadastrar Visitante</h3>
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
        <h3>Visitantes Cadastrados</h3>
      </div>
      <div class="col-12 row g-3">
        <div class="col-2">
          <select class="form-select" name="busca" id="busca">
            <option value="nome">Nome</option>
            <option value="email">Email</option>
          </select>
        </div>
        <div class="col-6">
          <input type="text" class="form-control" id="buscarNome" name="buscarNome" placeholder="Buscar Visitante">
        </div>
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">E-mail</th>
            <th scope="col">Nome</th>
            <th class="text-center" scope="col">Alterar</th>
            <th class="text-center" scope="col">Excluir</th>
          </tr>
        </thead>
        <tbody id="alterar">
          <!-- Inicio da Lista de Visitantes para Alterar/Excluir -->
          <!-- Carregamento da Lista de Visitantes via JS -->
          <!-- Fim da Lista de Visitantes para Alterar/Excluir -->
        </tbody>
      </table>
    </div>
  </div>
  <script src="../Framework/js/jquery-3.6.4.js"></script>
  <script src="../Framework/js/popper.min.js"></script>
  <script src="../Framework/js/bootstrap.js"></script>
  <script src="../js/js-cadastro-usuarios.js"></script>
  <script src="../js/js-cadastro-visitante.js"></script>
  <script src="../Framework/jQuery-Mask-Plugin/jquery.mask.js"></script>
</body>

</html>