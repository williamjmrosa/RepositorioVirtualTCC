<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/categoria.class.php';
if(!isset($_GET['TCC'])){
    header('Location: ../visao/index.php');
}else{
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
    <link rel="icon" type="image/jpg" href="../img/icone.png" />

</head>

<body class="container-fluid m-0 p-0">
    <div class="fundo-primario">
        <div class="mb-2">
            <nav class="p-2" id="menu">
                <div class="d-inline-block w-50">
                    <h3 class="home">TCC AQUI</h3>
                    <a class="btn fundo-secundario fw-bold" href="index.php">Home</a>
                    <a class="btn fundo-secundario fw-bold" href="#">Contatos</a>
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
                <div class="text-center">
                    <h3><?php echo $tcc['titulo']?></h3>
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
    <script src="../Framework/PDF/build/pdf.js"></script>
    <link rel="stylesheet" href="../Framework/PDF/web/viewer.css">
</body>

</html>