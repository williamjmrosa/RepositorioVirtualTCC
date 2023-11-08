<?php
session_start();
include_once('../dao/categoriadao.class.php');
include_once('../Modelo/categoria.class.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <title>TCC AQUI</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../Framework/css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
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
              <a class="btn fundo-secundario fw-bold m-1" href="#"><img id="img-login" class="me-4" src="../img/login.png" />Login</a>
              <ul class="fundo-secundario p-2 fw-bold text-start">
                <li>
                  <a href="../visao/telaCadastroCurso.php">Cadastrar Curso</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroCampus.php">Cadastrar Campus</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroCategoria.php">Cadastrar Categoria</a>
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
                  <a href="../visao/telaCadastroBibliotecario.php">Cadastrar Bibliotecário</a>
                </li>
                <li>
                  <a href="../visao/telaCadastroAdm.php">Cadastrar Administrador</a>
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
      <form id="cadastrarCategoria" class="row" method="post" action="../controle/categoria-controle.php?OP=1">
        <!-- Mensagens de Alerta do retorno do Cadastro -->
        <?php
        
          if (isset($_SESSION['erros']) && is_array(unserialize($_SESSION['erros']))) {
            $erros = unserialize($_SESSION['erros']);
            unset($_SESSION['erros']);
            $msg = "";
            foreach ($erros as $erro) {
              $msg = $msg . '<p class="m-0"> ' . $erro . '</p>';
            }
            $sucesso = false;
          }elseif(isset($_SESSION['erros'])){
            $sucesso = false;
            $msg = $_SESSION['erros'];
            unset($_SESSION['erros']);
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
        <div>
          <h3>Cadastrar Categoria</h3>
        </div>
        <div class="col-12">
          <label for="nomeCategoria" class="form-label">Nome Categoria</label>
          <input type="text" class="form-control" id="nomeCategoria" name="nomeCategoria" placeholder="Nome Categoria">
        </div>
        <div class="col-12 mt-3 mb-3" id="nomesAlternativos">
          <div class="alternativas">
          </div>
          <div class="col-12">
            <label for="nomeAlternativo" class="form-label me-2">Nome Alternativo</label>
            <input type="text" class="form-control d-inline w-50" id="nomeAlternativo" name="nomeAlternativo[]">
            <a href="#" class="btn btn-primary ms-3" onclick="adicionarNomeAltenativo(this)">+</a>
          </div>
        </div>
        <div class="col-12">
          <label class="form-label d-block">É categoria Sub</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="eSub" id="eSub" value="true">
            <label class="form-check-label" for="eSub">Sim</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="eSub" id="nSub" value="false" checked>
            <label class="form-check-label" for="nSub">Não</label>
          </div>
        </div>
        <div class="col-6 d-none" id="catPrincipal">
          <label class="form-label">Categoria Pai</label>
          <select class="form-select" size="4" name="principal" id="principal">
            <?php
              $cDAO = new CategoriaDAO();
              $categorias = $cDAO->listarCategoria();
              if(is_array($categorias) && count($categorias) > 0) {
                foreach($categorias as $c){
                  echo '<option value="'.$c->idCategoria.'">'.$c->nome.'</option>';
                }
              }else{
                echo '<option value="-1">Nenhuma Categoria Cadastrada</option>';
              }
            ?>
          </select>
        </div>
        <div class="col-12 mt-3">
          <button type="submit" class="btn btn-primary">Cadastrar Categoria</button>
        </div>
      </form>
    </div>
    <div class="row g-3 m-4 cadastro w-auto">
      <div class="col-12">
        <h3>Categorias Cadastradas</h3>
      </div>
      <div class="col-12">
        <label class="form-label" for="buscarNome"> Buscar Nome de Categoria</label>
        <input type="text" class="form-control" id="buscarNome" name="buscarNome" placeholder="Buscar nome categoria">
      </div>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nome</th>
              <th scope="col">E Sub</th>
              <th scope="col">Categoria Principal</th>
              <th scope="col">Alterar</th>
              <th scope="col">Excluir</th>
            </tr>
          </thead>
          <tbody id="alterar">
            <!-- Inicio da Lista de Categoria para Alterar/Excluir -->
            <!-- Carregamento da Lista de Categorias via JS -->
            <!-- Fim da Lista de Categoria para Alterar/Excluir -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="../Framework/js/jquery-3.6.4.js"></script>
  <script src="../Framework/js/popper.min.js"></script>
  <script src="../Framework/js/bootstrap.js"></script>
  <script src="../js/js-cadastro-categoria.js"></script>
</body>

</html>