<?php session_start();?>
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
                    <div class="div-login">
                        <ul id="login">
                            <li>
                                <a class="btn fundo-secundario fw-bold m-1" href="#"><img id="img-login" class="me-4" src="../img/login.png"/>Login</a>
                                <ul class="fundo-secundario p-2 fw-bold text-start">
                                    <li>
                                        <a class="" href="../visao/telaCadastroCurso.php">Cadastrar Curso</a>    
                                    </li>
                                    <li>
                                        <a class="" href="../visao/telaCadastroTCC.php">Cadastrar TCC</a>    
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            
        </div>
        <div class="corpo">
            <div class="cadastro w-auto">
                <form class="row g-3" action="../controle/curso-controle.php?OP=1" method="post">
                    <!-- Mensagens de Alerta do retorno do Cadastro -->
                    <?php
                      $sucesso = false;
                      try{
                            if(isset($_SESSION['erros'])){
                                $erros = unserialize($_SESSION['erros']);
                                unset($_SESSION['erros']);
                                $msg_erro = "";
                                foreach($erros as $erro){
                                    $msg_erro = $msg_erro.'<p class="m-0"> '.$erro.'</p>';
                                }
                                throw new Exception($msg_erro);
                            }else if(isset($_SESSION['msg'])){
                                $sucesso = true;
                                $msg = $_SESSION['msg'];
                                unset($_SESSION['msg']);
                                throw new Exception($msg);

                          }
                      }catch (Exception $ex){ 
                    ?>
                    <div class="alert <?php if($sucesso){echo 'alert-success';}else{echo 'alert-danger';} ?>" role="alert">
                          <?php
                              echo $ex->getMessage();
                          ?>
                    </div>
                    <?php
                      }
                    ?>
                    <!-- Fim da mensagens de Alerta do retorno do Cadastro -->
                  <div><h3>Cadastrar Curso</h3></div>
                  <div class="col-12">
                    <label for="nome" class="form-label">Curso</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                  </div>
                  <div class="col-md-12">      
                    <input class="form-check-input" value="0" type="radio" name="ensino" id="medio" checked>
                    <label class="form-check-label" for="medio">
                      Ensino Médio
                    </label>
                    <input class="form-check-input" value="1" type="radio" name="ensino" id="superior">
                    <label class="form-check-label" for="superior">
                      Ensino Superior
                    </label>
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