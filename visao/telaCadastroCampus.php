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
                <form class="row g-3" action="../controle/campus-controle.php?OP=1" method="post">
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
                  <div><h3>Cadastrar Campus</h3></div>
                  <div class="col-12">
                    <label for="nome" class="form-label">Campus</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                  </div>
                  <div class="col-12">
                        <label class="form-label">Lista de Cursos</label>
                        <select id="lista" name="lista" class="form-select" size="3" aria-label="Lista de Cursos">
                            <!--<option selected value="teste">Lista de Cursos</option>-->
                            <?php
                                $cDAO = new CursoDAO();
                                $cursos = $cDAO->listarCursos();
                                if(is_array($cursos)){
                                    foreach($cursos as $c){
                                ?>
                                    <option onclick="lista(this)" value="<?php echo $c->idCurso;?>"><?php echo $c->nome; ?></option>
                                <?php
                                    }
                                }
                                ?>
                        </select>
                    <label class="form-label">Cursos selecionados</label>
                        <select id="cursos" name="cursos[]" class="form-select" size="3" multiple>
                        </select>
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
        <script src="../js/js-cadastro-campus.js"></script>
    </body>
</html>