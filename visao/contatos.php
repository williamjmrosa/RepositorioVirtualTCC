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

<body class="container-fluid m-0 p-0 fundo-secundario">
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
    <div class="corpo">
        <div class="cadastro w-auto">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-center">Informações </h4>
                </div>
                <div class="col-6">
                    <p class="fw-bold">Trabalho de Conclusão de Curso (TCC)</p>
                    <p><strong>Titulo do Trabalho:</strong> Repositório virtual de Trabalhos de Conclusão de Curso</p>
                    <p><strong>Titulo do Projeto:</strong> TCC AQUI </p>
                    <p><strong>Ano:</strong> 2024</p>
                    <p><strong>Autor:</strong> William José de Moura da Rosa</p>
                    <p><strong>Orientador:</strong>Dr. Dieison Soares Silveira</p>
                    
                </div>
                <div class="col-6 border-start border-dark border-3">
                    <p><strong>Resumo:</strong> TCC AQUI</p>
                    <p>O TCC AQUI é um repositório virtual desenvolvido com o objetivo de 
                        criar um ambiente onde alunos e professores possam acessar os tccs
                        que foram submetidos e publicados na Instituição de Ensino de forma simples.
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="../Framework/js/jquery-3.6.4.js"></script>
    <script src="../Framework/js/pdf.min.js"></script>
    <script src="../Framework/js/popper.min.js"></script>
    <script src="../Framework/js/bootstrap.js"></script>
    <script src="../js/js-tela-principal.js"></script>
</body>

</html>