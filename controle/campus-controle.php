<?php
session_start();
include_once '../util/padronizacao.class.php';
include_once '../Modelo/campus.class.php';
include_once '../dao/campusdao.class.php';
include_once '../util/validacao.class.php';

if(isset($_GET['OP'])){

    $OP = filter_var(@$_REQUEST['OP'],FILTER_SANITIZE_NUMBER_INT);
    echo 'entrou';
    switch ($OP) {
        //Cadastrar Campus
        case 1:
            $erros = array();
            
            if(!(isset($_POST['nome']) && isset($_POST['cursos']))){
                $erros[] = "Campos não existem";
            }
            
            $nome = filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS);
            
            
            if(!Validacao::validarNome($nome)){
                $erro[] = "Nome para Curso invalido!";
            }

            $cursos = array();
            foreach ($_POST['cursos'] as $v) {
                $cursos[] = $v;
            }


            if(count($erros) == 0){
                $c = new Campus;
                $c->nome = $nome;
                $c->cursos = $cursos;

                $cDAO = new CampusDAO;
                $c->idCampus = $cDAO->cadastrarCampus($c);
                for ($i=0; $i < count($c->cursos); $i++) { 
                    # code...
                   $cDAO->cadastrarCursoNoCampus($c->idCampus,intval($c->cursos[$i]));
                }

                $_SESSION['msg'] = 'Campus Cadastrado!\n';
            }
            header('location: ../visao/telaCadastroCampus.php');
            break;
        
        default:
            # code...
            break;
    }

}
?>