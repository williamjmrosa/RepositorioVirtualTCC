<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/categoria.class.php';
if (!isset($_GET['TCC'])) {
    header('Location: ../visao/index.php');
} else {
    $idTCC = filter_var($_GET['TCC'], FILTER_SANITIZE_NUMBER_INT);
    $tccDAO = new TccDAO();
    $tcc = $tccDAO->buscarTCCID($_GET['TCC']);
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
    <link rel="icon" type="image/jpg" href="../img/icone.png"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body class="container-fluid m-0 p-0">
    <div class="fundo-primario">
        <div class="mb-2">
            <nav class="p-2 d-flex flex-column align-items-start" id="menu">
                <div class="row w-100">
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
                </div>
                <div class="text-center row g-3 w-100">
                    <div class="col-lg-10 text-center">
                        <h3><?php echo $tcc['titulo'] ?></h3>
                    </div>
                    <div class="col-lg-2 text-end">
                        <form action="../controle/tcc-controle.php?OP=4" method="post">
                            <input type="hidden" name="idTCC" value="<?php echo $idTCC ?>">
                            <button type="submit" class="btn fundo-secundario fw-bold">Baixar TCC</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

    </div>
    <div class="corpo row g-3">

        <div class="col-lg-12">
            <?php
            echo "<embed src='$tcc[localPDF]' type='application/pdf' width='100%' height='100%' />";
            ?>
        </div>

    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
</body>

</html>