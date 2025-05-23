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
if(isset($_SESSION['usuario'])){
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

}else{
    $tipo = 'Null';
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
                        <a class="btn fundo-secundario fw-bold" href="../visao/contatos.php">Contatos</a>
                        <a class="btn fundo-secundario fw-bold" href="../visao/tccMarcado.php">Marcados</a>
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
    <div class="corpo d-flex">
        <!-- Inicio Filtro Esquerdo -->
        <div class="filtro-esquerdo p-1 col-3">
            <div id="filtrosSelecionados" class="mb-3">
                <form id="formFiltrar" action="../controle/tcc-controle.php?OP=5" method="post">
                    <?php
                    if (isset($_SESSION['filtros'])) {
                        //print_r(unserialize($_SESSION['filtros']));
                        foreach (unserialize($_SESSION['filtros']) as $key => $value) {
                            if ($key == 'campus') {
                                echo "<span class='badge bg-danger me-1'> <input type='hidden' name='campus' value='" . $value . "'><input class='btn-close' type='button' onclick='removerCampus(this)'></span>";
                            } elseif ($key == 'categorias') {
                                foreach ($value as $categoria) {
                                    echo "<span class='badge bg-info me-1'> <input type='hidden' name='categorias[]' value='" . $categoria . "'><input class='btn-close' type='button' onclick='removerCategoria(this)'></span>";
                                }
                            } elseif ($key == 'paginaAtual') {
                                $paginaAtual = $value;
                                echo "<input type='hidden' name='$key' value='" . $value . "'>";
                            } elseif ($key == 'autor') {
                                echo "<span class='badge bg-danger me-1'> Autor: $value <input type='hidden' name='autor' value='$value'><input class='btn-close' type='button' onclick='remover(this)'></span>";
                            } elseif ($key == 'titulo') {
                                echo "<span class='badge bg-danger me-1'> Titulo: $value <input type='hidden' name='titulo' value='$value'><input class='btn-close' type='button' onclick='remover(this)'></span>";
                            } elseif ($key == 'curso') {
                                echo "<span class='badge bg-success me-1'> $value <input type='hidden' name='curso' value='$value'><input class='btn-close' type='button' onclick='remover(this)'></span>";
                            } elseif ($key == 'orientador') {
                                echo "<span class='badge bg-danger me-1'> $value <input type='hidden' name='orientador' value='$value'><input class='btn-close' type='button' onclick='remover(this)'></span>";
                            } else {
                                echo "<input type='hidden' name='$key' value='$value'>";
                            }
                        }
                    }
                    ?>
                </form>
            </div>
            <div>
                <input type="text" id="buscarCampus" class="form-control mb-2" placeholder="Buscar campus">
            </div>
            <details id="listaCampus" open>
                <!-- Lista de Campus -->
                <!-- Carregado ao carregar a tela via JS -->
            </details>
            <hr>
            <div>
                <input type="text" id="buscarCategoriaPrincipal" class="form-control mb-2" placeholder="Buscar Categoria Principal">
            </div>
            <details class="m-1" id="listarCategoriasPrincipal">
                <!-- Lista de Categorias -->
                <!-- Carregado ao carregar a tela via JS -->
            </details>
            <hr>
        </div>
        <!-- Fim Filtro Esquerdo -->
        <!-- Inicio Conteudo -->
        <div class="conteudo ms-1 me-1 col-6">
            <?php


            if (isset($_SESSION['tccs'])) {
                $listaTCC = unserialize($_SESSION['tccs']);
                unset($_SESSION['tccs']);
                unset($_SESSION['filtros']);
            } elseif (!isset($_SESSION['listaTCC']) || isset($_GET['pagina'])) {
                $tccDAO = new TCCDAO();
                $listaTCC = $tccDAO->listarTCC(isset($_GET['pagina']) ? $_GET['pagina'] : 1);
            }

            if (isset($_SESSION['msg'])) {
                echo "<div class='alert alert-success m-2' role='alert'>" . $_SESSION['msg'] . "</div>";
                unset($_SESSION['msg']);
            }

            if (isset($_SESSION['erro'])) {
                echo "<div class='alert alert-danger m-2' role='alert'>" . $_SESSION['erro'] . "</div>";
                unset($_SESSION['erro']);
            }

            if (isset($_SESSION['erros'])) {
                foreach ($_SESSION['erros'] as $erro) {
                    echo "<div class='alert alert-danger m-2' role='alert'>" . $erro . "</div>";
                }
                unset($_SESSION['erros']);
            }

            if (!empty($listaTCC)) {
                foreach ($listaTCC as $tcc) {

                    echo "<div class='card mb-3 ms-2 me-2' style='max-width: 100%;'>
                            <a href='../visao/lerTCC.php?TCC=" . $tcc->idTCC . "''>
                                <div class='row g-0'>
                                    <div class='col-md-2'>
                                        <img class='w-100 mt-5' src='../img/PDF1.png" . /* gerarImagem($tcc->localPDF, $tcc->idTCC) .*/ "' class='img-fluid rounded-start' alt='...'>
                                    </div>
                                    <div class='col-md-10'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>$tcc->titulo</h5>
                                            <p class='card-text m-0'>Autor: " . $tcc->aluno->nome . "</p>
                                            <p class='card-text m-0'>Campus: " . $tcc->campus->nome . "</p>
                                            <p class='card-text m-0'>Curso: " . $tcc->curso->nome . "</p>
                                            <p class='card-text m-0'>Orientadores: " . $tcc->verOrientadores() . "</p>
                                            <p class='card-text m-0'>Categorias: " . $tcc->verCategorias() . "</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class='col-md-12'>
                                <div class='card-footer'>";

                    if (isset($_SESSION['usuario'])) {
                        $fDAO = new FavoritoDAO();
                        if ($fDAO->verificarFavorito($tcc->idTCC)) {
                            echo "<button class='btn btn-light border me-2' id='favorito' type='button' onclick='removerFavorito(this," . $tcc->idTCC . ")'><i class='bi bi-star-fill me-1'></i>Favorito</button>";
                        } else {
                            echo "<button class='btn btn-light border me-2' id='favorito' type='button' onclick='adicionarFavorito(this," . $tcc->idTCC . ")'><i class='bi bi-star me-1'></i>Favorito</button>";
                        }
                    } else {
                        echo "<button class='btn btn-light border me-2' id='favorito' type='button' onclick='adicionarFavorito(this," . $tcc->idTCC . ")'><i class='bi bi-star me-1'></i>Favorito</button>";
                    }
                    if($tipo == 'Professor'){
                        echo "<button class='btn btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#indicar' id='btn-indicar' onclick='clicarIndicar(this," . $tcc->idTCC . ")'>Indicar</button>
                            ";
                    }

                    echo "      </div>
                            </div>
                        </div>";

                }
            } else {
                echo "<div class='alert alert-warning m-2' role='alert'>Nenhum TCC encontrado</div>";
            }

            ?>
            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination align-items-center justify-content-center">
                    <?php
                    if (!isset($paginaAtual)) {

                        $paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                    }

                    $totalPaginas = isset($_SESSION['totalPaginas']) ? $_SESSION['totalPaginas'] : 1;



                    if ($paginaAtual > 1) {
                        echo "<li class='page-item'><a id='anterior' class='page-link' href='?pagina=" . ($paginaAtual - 1) . "'>Anterior</a></li>";
                    } elseif ($paginaAtual == 1 && $totalPaginas > 1) {
                        echo "<li class='page-item disabled'><a class='page-link' href='?pagina=" . ($paginaAtual - 1) . "'>Anterior</a></li>";
                    }
                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        if ($i == $paginaAtual) {
                            echo "<li class='page-item active'><a class='page-link pagina' href='?pagina=" . $i . "'>" . $i . "</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link pagina' href='?pagina=" . $i . "'>" . $i . "</a></li>";
                        }
                    }
                    if ($paginaAtual < $totalPaginas) {
                        echo "<li class='page-item'><a id='proximo' class='page-link ' href='?pagina=" . ($paginaAtual + 1) . "'>Próximo</a></li>";
                    } elseif ($totalPaginas == $paginaAtual) {
                        echo "<li class='page-item disabled'><a class='page-link' href='?pagina=" . ($paginaAtual + 1) . "'>Próximo</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>

        <!-- Fim Conteudo -->
        <!-- Inicio Filtro Direito -->
        <div class="filtro-direito ms-1 col-3">
            <div class="mt-4 ms-1 me-1">
                <input type="text" id="buscarCurso" class="form-control mb-2" placeholder="Buscar Curso">
            </div>
            <details class="m-1" id="listaCursos">
                <!-- Lista de Cursos -->
                <!-- Carregado ao carregar a tela via JS -->
            </details>
            <hr>
            <div>
                <input type="text" id="buscar" class="form-control mb-2 buscarCategoriaSecundaria" placeholder="Buscar Sub Categoria" oninput="buscarCategoriaSecundaria(this)">

                <details class="m-1" id="listaSubCategorias">
                    <!-- Lista de Sub Categorias -->
                    <!-- Carregado ao carregar a tela via JS -->
                </details>
                <hr>
            </div>
        </div>
        <!-- Fim Filtro Direito -->
        <!-- MODAL -->
        <?php 
            if($tipo == 'Professor'){
        ?>
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
                                        <option value="" selected>Selecione uma Instituuição</option>
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
                                        <option value="null" selected>Selecione um curso</option>
                                        <!-- Lista de Cursos -->
                                        <!-- Carregado ao carregar a tela via JS -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="alunos" class="form-label">Indicar para Aluno</label>
                                    <input type="text" class="form-control" id="searchInputAluno" placeholder="Buscar Alunos">
                                    <select class="form-select" name="alunos" id="alunos" size="3">
                                        <option value="null" selected>Selecione um Aluno</option>
                                        <!-- Lista de Alunos -->
                                        <!-- Carregado ao carregar a tela via JS -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alunos Selecionados</label>
                                    <div id="alunoSelecionado">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <label for="tcc" class="form-label">TCC</label> -->
                                    <input type="hidden" class="form-control" id="tcc" name="idTCC">
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
        <?php
            }
        ?>
        <!-- FIM MODAL -->

    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/pdf.min.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
    <script src="../js/js-cadastro-favorito.js"></script>
</body>

</html>