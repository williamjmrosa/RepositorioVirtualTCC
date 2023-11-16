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
    <link rel="icon" type="image/jpg" href="../img/icone.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="container-fluid m-0 p-0">
    <div class="fundo-primario">
        <div class="mb-2">
        <nav class="p-2 row" id="menu">
                <div class="col-6">
                    <h3 class="home">TCC AQUI</h3>
                    <a class="btn fundo-secundario fw-bold" href="index.php">Home</a>
                    <a class="btn fundo-secundario fw-bold" href="#">Contatos</a>
                    <a class="btn fundo-secundario fw-bold" href="#">TCC</a>

                </div>
                <div class="div-login col-6">
                    <ul id="login">
                        <!-- Menu de Login -->
                        <!-- Carregado via js/jquery -->
                    </ul>
                </div>
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
            </nav>
        </div>

    </div>
    <div class="corpo fundo-primario">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 m-5 w-75">
                    <div class="card m-5">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <form action="../controle/login-controle.php" method="post">
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
                                <div class="form-group">
                                    <label for="email">Matricula/Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Digite seu email">
                                </div>
                                <div class="form-group">
                                    <label for="senha">Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha">
                                </div>
                                <div class="form-group text-center mt-3 mb-3">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" value="1" class="form-check-input" name="tipoAcesso" id="aluno" checked>
                                        <label class="form-check-label" for="aluno">Aluno</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" value="2" class="form-check-input" name="tipoAcesso" id="professor">
                                        <label class="form-check-label" for="professor">Professor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" value="3" class="form-check-input" name="tipoAcesso" id="visitante">
                                        <label class="form-check-label" for="visitante">Visitante</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" value="4" class="form-check-input" name="tipoAcesso" id="bibliotecario">
                                        <label class="form-check-label" for="bibliotecario">Bibliotecário</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" value="5" class="form-check-input" name="tipoAcesso" id="adm">
                                        <label class="form-check-label" for="adm">Administrador</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                            <div class="text-center mt-3">
                                <p>Não possui uma conta? <a href="../visao/telaCadastroVisitante.php">Cadastre-se com Visitante</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
</body>

</html>