<?php
session_start();
include_once '../dao/campusdao.class.php';
include_once '../Modelo/campus.class.php';
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
            <li>
              <a class="btn fundo-secundario fw-bold m-1" href="#"><img id="img-login" class="me-4"
                  src="../img/login.png" />Login</a>
              <ul class="fundo-secundario p-2 fw-bold text-start">
                <li>
                  <a href="../visao/telaCadastroCurso.php">Cadastrar Curso</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroCampus.php">Cadastrar Campus</a>
                </li>
                <li>
                  <a href="../visao//telaCadastroCategoria.php">Cadastrar Categoria</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroAluno.php">Cadastrar Aluno</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroProfessor.php">Cadastrar Professor</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroVisitante.php">Cadastrar Visitante</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroTCC.php">Cadastrar TCC</a>
                </li>
              </ul>

            </li>
          </ul>
        </div>
      </nav>
    </div>

  </div>
  <div class="corpo">
    <div class="cadastro w-auto">
      <form class="row g-3" method="POST" action="../controle/aluno-controle.php?OP=1">
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
            <h3>Cadastrar Aluno</h3>
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
        <div class="col-lg-6">
          <label for="rg" class="form-label">RG</label>
          <input type="text" class="form-control" id="rg" name="rg" placeholder="RG">
        </div>
        <div class="col-lg-6">
          <label for="cpf" class="form-label">CPF</label>
          <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF">
        </div>
        <div class="col-lg-12">
          <label for="telefone" class="form-label">Telefone</label>
          <input type="text" class="form-control w-25" id="telefone" name="telefone" placeholder="Telefone">
        </div>
        <div class="col-md-2">
          <label for="cep" class="form-label">CEP</label>
          <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP">
        </div>
        <div class="col-md-6">
          <label for="cidade" class="form-label">Cidade</label>
          <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade">
        </div>
        <div class="col-md-4">
          <label for="uf" class="form-label">Estado</label>
          <input type="text" class="form-control" id="uf" name="uf" placeholder="Estado">
        </div>
        <div class="col-md-6">
          <label for="rua" class="form-label">Rua</label>
          <input type="text" class="form-control" id="rua" name="rua" placeholder="Rua">
        </div>
        <div class="col-md-4">
          <label for="bairro" class="form-label">Bairro</label>
          <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro">
        </div>
        <div class="col-md-2">
          <label for="numero" class="form-label">Número</label>
          <input type="text" class="form-control" id="numero" name="numero" placeholder="Número">
        </div>
        <div class="col-lg-12">
          <label for="complemento" class="form-label">Complemento</label>
          <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Complemento">
        </div>
        <div class="col-lg-12">
          <label for="campus" class="form-label">Campus</label>
          <select class="form-select" id="campus" size="4" name="campus">
            <option selected>Escolha...</option>
            <?php
            $campusDAO = new CampusDAO();
            $campi = $campusDAO->listarCampus();
            if(is_array($campi)){
              foreach ($campi as $campus) {
                echo "<option onClick='campusSelecionado(this)' value='$campus->idCampus'>$campus->nome</option>";
              }
            }
            ?>
            </select>
        </div>
        <div class="col-lg-12">
          <label for="curso" class="form-label">Curso</label>
          <select class="form-select" id="curso" size="4" name="curso">
            <option value="0" selected>Selecione um campus...</option>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
      </form>
    </div>
  </div>
  <script src="../Framework/js/jquery-3.6.4.js"></script>
  <script src="../Framework/js/popper.min.js"></script>
  <script src="../Framework/js/bootstrap.js"></script>
  <script src="../js/js-cadastro-usuarios.js"></script>
  <script src="../Framework/jQuery-Mask-Plugin/jquery.mask.js"></script>
</body>

</html>