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
                    <div class="div-login align-items-end">
                        <a class="btn fundo-secundario fw-bold m-1" href="#" id="login"><img class="me-4" src="../img/login.png"/>Login</a>
                            <ul class="fundo-secundario p-2 fw-bold text-start">
                            <li>
                                <a class="" href="../visao/telaCadastroCurso.php">Cadastrar Curso</a>    
                            </li>
                            <li>
                                <a class="" href="../visao/telaCadastroTCC.php">Cadastrar TCC</a>    
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            
        </div>
        <div class="corpo">
            <div class="cadastro w-auto">
                <form class="row g-3">
                    <div><h3>Cadastrar TCC</h3></div>
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                    </div>
                    <div class="col-md-6">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="col-md-6">
                      <label for="senha" class="form-label">Senha</label>
                      <input type="password" class="form-control" id="senha" name="senha">
                    </div>
                    <div class="col-12">
                      <label for="endereco" class="form-label">Endereço</label>
                      <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua xxx 18 bairro">
                    </div>
                    <div class="col-md-6">
                      <label for="cidade" class="form-label">Cidade</label>
                      <input type="text" class="form-control" id="cidade" name="cidade">
                    </div>
                    <div class="col-md-4">
                      <label for="estado" class="form-label">Estado</label>
                      <select id="estado" name="estado" class="form-select">
                        <option selected>Selecione...</option>
                        <option>...</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="cep" class="form-label">CEP</label>
                      <input type="text" class="form-control" id="cep" name="cep">
                    </div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="../Framework/js/jquery-3.6.4.js"></script>
        <script src="../Framework/js/popper.min.js"></script>
        <script src="../Framework/js/bootstrap.js"></script>
    </body>
</html>