<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/categoria.class.php';
include_once '../dao/favoritodao.class.php';
include_once '../dao/campusdao.class.php';
include_once '../dao/cursodao.class.php';

if(isset($_SESSION['usuario'])){
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);
}else{
    $erros[] = "Voce precisa estar logado para acessar favoritos/indicados";
    $_SESSION['erros'] = $erros; 
    header('location:../index.php');
}

function gerarImagem($caminho_pdf, $id)
{

    if (!file_exists('../TCC/' . $id . '/capa.jpg')) {
        // Define o caminho do diretório onde a imagem será salva
        $diretorio_imagens = "../TCC/$id/";
        // Comando Ghostscript para extrair a imagem da capa
        $comando_ghostscript = 'gs -dFirstPage=1 -dLastPage=1 -sDEVICE=jpeg -dJPEGQ=100 -r300 -o "' . $diretorio_imagens . 'capa.jpg" "' . $caminho_pdf . '"';
        // Executa o comando Ghostscript para extrair a imagem da capa
        $output = shell_exec($comando_ghostscript);
    }
    // Obtém o caminho completo da imagem
    $caminho_imagem = '../TCC/' . $id . '/capa.jpg';
    
    return $caminho_imagem;
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
                            <a class="btn fundo-secundario fw-bold" href="#">Contatos</a>
                            <a class="btn fundo-secundario fw-bold" href="#">TCC</a>

                    </div>
                    <div class="div-login col-6">
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
    <div class="corpo">
        <div class="d-flex h-100">
            <div class="col-3 fundo-secundario border-end rounded-start-5 border border-dark border-4">
                <!-- Lista de TCC indicados -->
                <div class="m-2 w-100 text-center fw-bold mt-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="checkFavorito">Favoritos</label>
                        <input class="form-check-input" type="radio" id="checkFavorito" name="checkTipo" value="favorito">
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="checkIndicado">Indicados</label>
                        <input class="form-check-input" type="radio" id="checkIndicado" name="checkTipo" value="indicado">
                    </div>
                    <div class="mb-3 pe-3 text-start">
                        <label class="form-label-inline" for="instituicao">Instituição</label>
                        <select class="form-select" name="instituicao" id="instituicao">
                            <option selected>Selecione uma instituição</option>
                            <?php
                            $campusDAO = new CampusDAO();
                            $instituicoes = $campusDAO->listarCampus();
                            foreach ($instituicoes as $instituicao) {
                                echo "<option value='$instituicao->idCampus'>$instituicao->nome</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 pe-3 text-start">
                        <label class="form-label-inline" for="curso">curso</label>
                        <select class="form-select" name="curso" id="curso">
                            <option selected>Selecione um curso</option>
                            <?php
                            $cursoDAO = new CursoDAO();
                            $cursos = $cursoDAO->listarCursos();
                            foreach ($cursos as $curso) {
                                echo "<option value='$curso->idCurso'>$curso->nome</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 pe-3 text-start">
                        <label class="form-label-inline" for="tcc">TCC</label>
                        <select class="form-select" name="tcc" id="tcc" size="10">
                            <option selected>Selecione um tcc</option>
                            <?php
                            $tccDAO = new TccDAO();
                            $tccs = $tccDAO->listarTodosTCC();
                            foreach ($tccs as $tcc) {
                                echo "<option value='$tcc->idTCC'>$tcc->titulo</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="m-2 listarTCC">

                </div>
                <!-- Fim da Lista de TCC indicados -->
            </div>
            <div class="col-9" style="height: 100%; background-color: #cce5ff;">
                <!-- TCC Selecionado -->
                <div class="m-2 w-100 text-center fw-bold mt-3">
                    <h1>Selecione um TCC</h1>
                </div>
                <div id="tccSelecionado">
                    <embed src="" type="">
                </div>
                <!-- Fim TCC Selecionado -->
            </div>
        </div>
    </div>
    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
    <script src="../js/js-tccMarcado.js"></script>
</body>

</html>