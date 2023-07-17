<?php
session_start();
include_once '../util/padronizacao.class.php';
include_once '../util/validacao.class.php';
include_once '../Modelo/curso.class.php';
include_once '../dao/cursodao.class.php';

if(isset($_GET['OP'])){

    $OP = filter_var(@$_REQUEST['OP'],FILTER_SANITIZE_NUMBER_INT);

    SWITCH ($OP){
        //Cadastrar Curso
        case 1:
            
            if(!(isset($_POST['nome']) && isset($_POST['ensino']))){
                header('location: ../index.php');
                break;
            }

            
            $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
                        
            $ensino = filter_var($_POST['ensino'],FILTER_SANITIZE_NUMBER_INT);

            $erro = array();

            if(!Validacao::validarNome($nome)){
                $erro[] = "Nome para Curso invalido!";
            }

            if(!($ensino == 0 || $ensino == 1)){
                $erro[] = "Ensino não Selecionado";
            }
            
            if(count($erro) == 0){    
                
                $c = new Curso;
                $c->nome = Padronizacao::padronizarNome($nome);
                $c->ensino = $ensino;
                
                $cDAO = new CursoDAO;
                $erroBanco = $cDAO->cadastrarCurso($c);
                
                if(isset($erroBanco)){
                    $erro[] = $erroBanco."\n";
                    $_SESSION['erros'] = $erro;
                    
                }else{
                    $_SESSION['msg'] = 'Curso Cadastrado!\n';
                }
            }else{
                $_SESSION['erros'] = serialize($erro);
            }


            header('location: ../visao/telaCadastroCurso.php');

        break;
    }

}

?>