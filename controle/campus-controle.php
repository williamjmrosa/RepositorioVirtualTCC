<?php
session_start();
include_once '../util/padronizacao.class.php';
include_once '../Modelo/campus.class.php';
include_once '../dao/campusdao.class.php';
include_once '../util/validacao.class.php';

if (isset($_GET['OP'])) {

    $OP = filter_var(@$_REQUEST['OP'], FILTER_SANITIZE_NUMBER_INT);
    switch ($OP) {
        //Cadastrar Campus
        case 1:
            $erros = array();

            if (!isset($_POST['nome'])) {
                $erros[] = "Campo nome não existe.";
            } elseif ($_POST["nome"] == "") {
                $erros[] = "Campo nome em branco";
            } else {
                $nome = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($nome,60)){
                    $erros[] = "Tamanho do nome invalido! (max 60 caracteres)";
                }
            }

            if (!isset($_POST["cursos"])) {
                $erros[] = "Campo curso não existe.";
            }

            if (!Validacao::validarNome($nome) && isset($nome)) {
                $erros[] = "Nome para Curso invalido!";
            }

            $cursos = array();
            foreach ($_POST['cursos'] as $v) {
                $cursos[] = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
            }


            if (count($erros) == 0) {
                $c = new Campus;
                $c->nome = $nome;
                $c->cursos = $cursos;

                $cDAO = new CampusDAO;
                $c->idCampus = $cDAO->cadastrarCampus($c);
                for ($i = 0; $i < count($c->cursos); $i++) {
                    # code...
                    $cDAO->cadastrarCursoNoCampus($c->idCampus, $c->cursos[$i]);
                }

                $_SESSION['msg'] = 'Campus Cadastrado!';
            } else {
                $_SESSION['erros'] = serialize($erros);
            }
            header('location: ../visao/telaCadastroCampus.php');
            break;
        //Alterar Campus
        case 2:

            $erros = array();

            if(!isset($_POST['id'])){
                $erros[] = "Campo id nao existe.";
            }else if($_POST["id"] == ""){
                $erros[] = "Campo id em branco";
            }else{
                $idCampus = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

            }

            if (!isset($_POST['nome'])) {
                $erros[] = "Campo nome não existe.";
            } elseif ($_POST["nome"] == "") {
                $erros[] = "Campo nome em branco";
            } else {
                $nome = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($nome,60)){
                    $erros[] = "Tamanho do nome invalido! (max 60 caracteres)";
                }
            }

            if (isset($_POST["cursos"])) {
                $cursos = array();
                foreach ($_POST['cursos'] as $v) {
                    $cursos[] = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if (!Validacao::validarNome($nome) && isset($nome)) {
                $erros[] = "Nome para Curso invalido!";
            }

            if (count($erros) == 0) {
                $c = new Campus;
                $c->idCampus = $idCampus;
                $c->nome = $nome;
                

                $cDAO = new CampusDAO;
                $campus = $cDAO->alterarCampus($c);
                if(isset($cursos)){
                    $c->cursos = $cursos;
                    for ($i = 0; $i < count($c->cursos); $i++) {
                        # code...
                        $cDAO->cadastrarCursoNoCampus($c->idCampus, $c->cursos[$i]);
                    }
                }

                if($campus){
                    $_SESSION['msg'] = 'Campus Alterado!';
                }else{
                    $erros[] = "Erro ao alterar campus!";
                    $_SESSION['erros'] = serialize($erros);
                }

            } else {
                $_SESSION['erros'] = serialize($erros);
            }
            header('location: ../visao/telaCadastroCampus.php');

            break;
        //Desativar/Ativar Campus
        case 3:

            $erros = array();
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $cDAO = new CampusDAO();
                $campus = $cDAO->alterarStatusCampus($id);

                if($campus){
                    $_SESSION['msg'] = "Campus ativado/desativado com sucesso!";
                }else{
                    $erros[] = "Erro ao ativar/desativar campus!";
                    $_SESSION['erros'] = serialize($erros);
                }

            }else{
                $erros[] = "Nenhum campus selecionado!";
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroCampus.php');

            break;
        //Remover Curso do Campus
        case 4:
            if(isset($_GET['idCurso']) && isset($_GET['idCampus'])){
                $idCurso = filter_var($_GET['idCurso'], FILTER_SANITIZE_NUMBER_INT);

                $idCampus = filter_var($_GET['idCampus'], FILTER_SANITIZE_NUMBER_INT);

                $cDAO = new CampusDAO();

                $campus = $cDAO->removerCursoDoCampus($idCurso,$idCampus);

                if($campus){
                    $_SESSION['msg'] = "Curso removido com sucesso!";
                }else{
                    $erros[] = "Erro ao remover curso!";
                    $_SESSION['erros'] = serialize($erros);
                }

            }else{
                $erros[] = "Nenhum Curso selecionado para remover!";
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroCampus.php');

            break;

        default:
            header('location: ../visao/telaCadastroCampus.php');
            break;
    }

}
