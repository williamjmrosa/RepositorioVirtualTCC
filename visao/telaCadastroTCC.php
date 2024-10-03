<?php
session_start();
include_once '../dao/alunodao.class.php';
include_once '../dao/cursodao.class.php';
include_once '../dao/campusdao.class.php';
include_once '../dao/professordao.class.php';
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/categoria.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/adm.class.php';
include_once '../Modelo/bibliotecario.class.php';

if(isset($_SESSION['usuario'])) {
  $user = unserialize($_SESSION['usuario']);
  $tipo = get_class($user);
  if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
    $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar tela de Cadastro TCC!";
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css/style.css">
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
      <form class="row g-3" method="POST" action="../controle/tcc-controle.php?OP=1" enctype="multipart/form-data">
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
        } elseif (isset($_SESSION['erro'])) {
          $sucesso = false;
          $msg = $_SESSION['erro'];
          unset($_SESSION['erro']);
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
        <div>
          <h3>Cadastrar TCC</h3>
        </div>
        <div class="col-lg-12">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título">
        </div>
        <div class="col-lg-12">
          <label for="autor" class="form-label">Autor TCC</label>
          <input type="text" class="form-control mb-2" id="buscaAluno" placeholder="Busca Autor (Nome Aluno/matricula)">
          <select class="form-select" id="autor" name="autor" size="4">
            <?php
            $alunoDAO = new AlunoDAO();
            $alunos = $alunoDAO->listarAlunos(false);
            foreach ($alunos as $aluno) {
              echo '<option value="' . $aluno->matricula . '">' . $aluno->nome . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-lg-6" id="divCampus">
          <label for="campusNome" class="form-label">Campus</label>
          <input type="hidden" id="campus" name="campus">
          <input type="text" disabled class="form-control" id="campusNome" placeholder="Campus">
        </div>
        <div class="col-lg-6" id="divCurso">
          <label for="cursoNome" class="form-label">Curso</label>
          <input type="hidden" id="curso" name="curso">
          <input type="text" disabled class="form-control" id="cursoNome" placeholder="Curso">
        </div>
        <div class="col-lg-12">
          <label for="descricao" class="form-label">Descrição</label>
          <textarea class="form-control" maxlength="600" id="descricao" name="descricao" rows="3"></textarea>
        </div>
        <div class="col-lg-6" id="divListaOrientador">
          <label for="listaOrientador" class="form-label">Orientador</label>
          <input type="text" class="form-control" id="buscaOrientador" placeholder="Busca Orientador (Nome Professor)">
          <select class="form-select mt-2" id="listaOrientador" size="4">
            <?php
            $professorDAO = new ProfessorDAO();
            $professores = $professorDAO->listarProfessores(false);
            foreach ($professores as $professor) {
              echo '<option value="' . $professor->matricula . '" onClick="listaOrientador(this)">' . $professor->nome . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-lg-6 mb-2" id="divOrientador">
          <label for="orientador" class="form-label">Orientador(es) Selecionado(s)</label>
          <select class="form-select" id="orientador" name="orientador[]" size="6" multiple>
          </select>
        </div>
        <div class="col-lg-6">
          <label for="categoriaPrincipal" class="form-label">Categoria Principal</label>
          <input type="text" class="form-control mb-2" id="buscaCategoriaPrincipal" placeholder="Busca Categoria (Nome)">
          <select class="form-select" id="categoriaPrincipal" size="6">
            <?php
            $categoriaDAO = new CategoriaDAO();
            $categorias = $categoriaDAO->listarCategoriaPrincipal();
            foreach ($categorias as $categoria) {
              echo '<option value="' . $categoria->idCategoria . '">' . $categoria->nome . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-lg-6">
          <label for="categoriaSecundaria" class="form-label">Categoria Secundaria</label>
          <input type="text" class="form-control mb-2" id="buscaCategoriaSecundaria" placeholder="Busca Categoria (Nome)">
          <select class="form-select" id="categoriaSecundaria" size="4">
            <?php
            $categoriaDAO = new CategoriaDAO();
            $categorias = $categoriaDAO->listarSubCategorias();
            foreach ($categorias as $categoria) {
              echo '<option value="' . $categoria->idCategoria . '">' . $categoria->nome . '</option>';
            }
            ?>
          </select>
          <button type="button" class="btn btn-primary mt-2 w-100" onclick="limpar()" id="btnSalvar">Limpar</button>
        </div>
        <div class="col-lg-12" id="categoriasSalvas">
          <h6>Categorias Selecionadas</h6>
        </div>
        <div class="col-lg-12">
          <label for="localPDF" class="form-label">Upload PDF</label>
          <input type="file" class="form-control" id="localPDF" name="localPDF" placeholder="Local PDF" accept="application/pdf">
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
            <option value="titulo">Titulo</option>
            <option value="nome">Autor</option>
            <option value="idTCC">ID TCC</option>
          </select>
        </div>
        <div class="col-6">
          <input type="text" class="form-control" id="buscarNome" name="buscarNome" placeholder="Buscar Visitante">
        </div>
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Titulo</th>
            <th scope="col">Autor</th>
            <th class="text-center" scope="col">Alterar</th>
            <th class="text-center" scope="col">Excluir</th>
          </tr>
        </thead>
        <tbody id="alterar">
          <!-- Inicio da Lista de TCC para Alterar/Excluir -->
          <!-- Carregamento da Lista de TCC via JS -->
          <!-- Fim da Lista de TCC para Alterar/Excluir -->
        </tbody>
      </table>
    </div>
  </div>
  <?php

  ?>
  <script src="../Framework/js/jquery-3.6.4.js"></script>
  <script src="../Framework/js/popper.min.js"></script>
  <script src="../Framework/js/bootstrap.js"></script>
  <script src="../Framework/he/he.js"></script>
  <script src="../js/js-cadastro-tcc.js">




  </script>
</body>

</html>