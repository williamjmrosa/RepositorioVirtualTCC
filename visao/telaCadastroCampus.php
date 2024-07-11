<?php
session_start();
include_once '../dao/cursodao.class.php';
include_once '../Modelo/curso.class.php';
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
                    <a class="btn fundo-secundario fw-bold" href="../visao/tccMarcado.php">TCC</a>
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
            <form class="row g-3" action="../controle/campus-controle.php?OP=1" method="post">
                <!-- Mensagens de Alerta do retorno do Cadastro -->
                <?php
                $sucesso = false;
                try {
                    if (isset($_SESSION['erros'])) {
                        $erros = unserialize($_SESSION['erros']);
                        unset($_SESSION['erros']);
                        $msg_erro = "";
                        foreach ($erros as $erro) {
                            $msg_erro = $msg_erro . '<p class="m-0"> ' . $erro . '</p>';
                        }
                        throw new Exception($msg_erro);
                    } else if (isset($_SESSION['msg'])) {
                        $sucesso = true;
                        $msg = $_SESSION['msg'];
                        unset($_SESSION['msg']);
                        throw new Exception($msg);
                    }
                } catch (Exception $ex) {
                ?>
                    <div class="alert <?php if ($sucesso) {
                                            echo 'alert-success';
                                        } else {
                                            echo 'alert-danger';
                                        } ?>" role="alert">
                        <?php
                        echo $ex->getMessage();
                        ?>
                    </div>
                <?php
                }
                ?>
                <!-- Fim da mensagens de Alerta do retorno do Cadastro -->
                <div>
                    <h3>Cadastrar Campus</h3>
                </div>
                <div class="col-12">
                    <label for="nome" class="form-label">Campus</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                </div>
                <div class="col-12">
                    <label class="form-label">Lista de Cursos</label>
                    <select id="lista" name="lista" class="form-select" size="6" aria-label="Lista de Cursos">

                        <?php
                        $cDAO = new CursoDAO();
                        $cursos = $cDAO->listarCursos();
                        if (is_array($cursos) && count($cursos) > 0) {
                            foreach ($cursos as $c) {
                        ?>
                                <option onclick="lista(this)" value="<?php echo $c->idCurso; ?>"><?php echo $c->nome . " | " . $c->mostrarEnsino(); ?></option>
                        <?php
                            }
                        } else {
                            echo '<option>Não existem Cursos Cadastrados</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Cursos selecionados</label>
                    <select id="cursos" name="cursos[]" class="form-select" size="4" multiple>
                    </select>
                    <div id="cursosCadastrados" class="row g-3 align-items-center">
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">Cadastrar</button>
                </div>
            </form>
        </div>
        <div class="row g-3 m-4 cadastro w-auto">
            <div class="col-12">
                <h3>Campus Cadastrados</h3>
            </div>
            <div class="col-12">
                <label class="form-label" for="buscarNome"> Buscar Nome de Campus</label>
                <input type="text" class="form-control" id="buscarNome" name="buscarNome" placeholder="Buscar nome campus">
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Ativo</th>
                        <th class="text-center" scope="col">Alterar</th>
                        <th class="text-center" scope="col">Ativo</th>
                    </tr>
                </thead>
                <tbody id="alterar">
                    <!-- Inicio da Lista de Campus para Alterar/Ativar/Desativar -->
                    <!-- Carregamento da Lista de Campus via JS -->
                    <!-- Fim da Lista de Categoria para Alterar/Ativar/Desativar -->
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-cadastro-campus.js"></script>
</body>

</html>