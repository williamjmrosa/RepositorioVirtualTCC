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

if (isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);
} else {
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
    <div class="container-fluid corpo h-100">
        <div class="d-flex h-100">
            <div class="col-3 h-100 fundo-secundario border-end rounded-start-5 border border-dark border-4">
                <!-- Lista de TCC indicados -->
                <div class="m-2 text-center fw-bold mt-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="checkFavorito">Favoritos</label>
                        <input class="form-check-input" type="radio" id="checkFavorito" name="checkTipo" value="favorito">
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="checkIndicado">Indicados</label>
                        <input class="form-check-input" type="radio" id="checkIndicado" name="checkTipo" value="indicado">
                    </div>
                    <div class="mb-3 pe-3 text-start">
                        <label class="form-label-inline" for="inst">Instituição</label>
                        <select class="form-select" name="inst" id="inst">
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
                        <label class="form-label-inline" for="cur">Curso</label>
                        <select class="form-select" name="cur" id="cur">
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
                        <label class="form-label-inline" for="tcclista">TCC</label>
                        <select class="form-select" name="tcclista" id="tcclista" size="10">
                            <option selected>Selecione um tcc</option>
                            <?php
                            if($tipo == 'Aluno'){
                                $fDAO = new FavoritoDAO();
                                $tccs = $fDAO->listarFavoritosAluno($user->matricula);
                            }else{
                                $tccDAO = new TccDAO();
                                $tccs = $tccDAO->listarTodosTCC();
                            }
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
            <div class="col-9 h-100 rounded-end-5 border border-dark border-4" style="background-color: #cce5ff;">
                <!-- TCC Selecionado -->
                <div class="m-2 w-100 mt-3 align-items-center">
                    <h1 class="text-center" id="titulo-tcc">Selecione um TCC</h1>
                    <div class="d-none" id="btns-tcc">
                        <div class="col-6">
                            <button class="btn btn-light btn-padrao fw-bold" id="favoritos" type="button" onclick=""><i class="bi bi-star-fill me-1"></i> Favoritos</button>
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#indicar" id="btn-indicar" onclick="">Indicar</button>
                        </div>
                        <div class="col-6 text-end">
                            <form action="../controle/tcc-controle.php?OP=4" method="post">
                                <input type="hidden" name="idTCC" value="<?php echo $idTCC ?>">
                                <button type="submit" class="btn btn-light fw-bold btn-padrao">Baixar TCC</button>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="m-2 w-100 container" id="tccSelecionado" style="height: 82%;">
                    <embed class="pb-2" src="" type="application/pdf" width="99%" height="100%">
                </div>
                <!-- Fim TCC Selecionado -->
            </div>
        </div>
        <!-- MODAL -->
        <div class="modal fade" id="indicar" tabindex="-1" aria-labelledby="modalTCCLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTCCLabel">Indicar TCC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="mb-3" id="formIndicar">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="instituicao" class="form-label">Instituuição</label>
                                    <input class="form-control" type="text" id="searchInputInstituicao" placeholder="Buscar Instituição">
                                    <select class="form-select" name="instituicao" id="instituicao" size="3">
                                        <option selected>Selecione uma Instituuição</option>
                                        <!-- Lista de Instituições -->
                                        <?php
                                        $campusDAO = new CampusDAO();
                                        $campus = $campusDAO->listarCampus();
                                        foreach ($campus as $c) {
                                            echo "<option value='" . $c->idCampus . "' onclick='carregarCursos(this)'>" . $c->nome . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="curso">Curso</label>
                                    <input type="text" class="form-control" id="searchInputCurso" placeholder="Buscar Cursos">
                                    <select class="form-select" name="curso" id="curso" size="3">
                                        <option selected>Selecione um curso</option>
                                        <!-- Lista de Cursos -->
                                        <!-- Carregado ao carregar a tela via JS -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tcc" class="form-label">TCC</label>
                                    <input type="text" class="form-control" id="tcc" name="idTCC">
                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="SalvarIndicar()">Salvar Indicação</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIM MODAL -->
    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
    <script src="../js/js-tcc-marcado.js"></script>
    <script src="../js/js-cadastro-favorito.js"></script>
</body>

</html>