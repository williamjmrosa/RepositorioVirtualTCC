<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../util/padronizacao.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/categoria.class.php';
include_once '../util/validacao.class.php';

function diretorioExiste($dir){
    if(is_dir($dir)){
        return true;
    }else{
        return false;
    }
}

function salvarArquivo($file,$id) {
    $raiz = '../TCC/'.$id;
    $destino = $raiz.'/' . $file['name'];

    if(diretorioExiste($raiz) == false){
        mkdir($raiz, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $destino)) {
        $tccDAO = new TCCDAO();
        $tccDAO->updateLocalPDF($id, $destino);
        return true;
    } else {
        return false;
    }
}

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);
    switch($OP){
        //Cadastrar TCC
        case 1:
            $erros = array();                        

            if(!isset($_POST['titulo'])){
                $erros[] = 'Campo Título não existe!';
            }elseif($_POST['titulo'] == ""){
                $erros[] = 'Campo Título em branco!';
            }elseif(strlen($_POST['titulo']) < 10){
                $erros[] = 'Titulo deve ter no minimo 10 caracteres!';
            }else{
                $titulo = filter_var($_POST['titulo'],FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['autor'])){
                $erros[] = 'Campo Autor não existe!';
            }elseif($_POST['autor'] == 0){
                $erros[] = 'Selecione um autor!';
            }else{
                $autor = filter_var($_POST['autor'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['campus'])){
                $erros[] = 'Campo Campus não existe!';
            }elseif($_POST['campus'] == ''){
                $erros[] = 'Campo Campus em branco!';
            }else{
                $campus = filter_var($_POST['campus'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['curso'])){
                $erros[] = 'Campo Curso.Undefinido!';
            }elseif($_POST['curso'] == ''){
                $erros[] = 'Campo Curso em branco!';
            }else{
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['descricao'])){
                $erros[] = 'Campo Descrição não existe!';
            }elseif($_POST['descricao'] == ""){
                $erros[] = 'Campo Descrição em branco!';
            }else{
                $descricao = filter_var($_POST['descricao'],FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Campo Local PDF não existe!';
            }elseif($_FILES['localPDF'] == ""){
                $erros[] = 'Campo Local PDF em branco!';
            }elseif(!validacao::validarPDF($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Arquivo não é um PDF!';
            }else{
                $localPDF = $_FILES['localPDF'];
            }

            if(!isset($_POST['categorias'])){
                $erros[] = 'Campo Categorias não existe!';
            }elseif($_POST['categorias'] == ""){
                $erros[] = 'Nenhum categoria foi marcada!';
            }else{
                $categorias = array();
                foreach($_POST['categorias'] as $categoria){
                    $categorias[] = filter_var($categoria, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(!isset($_POST['orientador'])){
                $erros[] = 'Campo Orientador não existe!';
            }elseif($_POST['orientador'] == ""){
                $erros[] = 'Campo Orientador em branco!';
            }else{
                $orientadores = array();
                foreach($_POST['orientador'] as $orientador){
                    $orientadores[] = filter_var($orientador, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(count($erros) == 0){
                
                $tcc = new TCC();
                $tcc->titulo = Padronizacao::padronizarNome($titulo);
                $tcc->descricao = $descricao;
                $tcc->aluno = new Aluno();
                $tcc->aluno->matricula = $autor;
                $tcc->curso = new Curso();
                $tcc->curso->idCurso = $curso;
                $tcc->campus = new Campus();
                $tcc->campus->idCampus = $campus;
                $tcc->orientador = $orientadores;
                $tcc->categorias = $categorias;
                

                $tccDAO = new TCCDAO();
                $tcc = $tccDAO->cadastrarTCC($tcc);

                if(!isset($_SESSION['erro']) && !isset($_SESSION['erros'])){
                    if(salvarArquivo($localPDF,$tcc->idTCC)){
                        $_SESSION['msg'] = 'TCC cadastrado com sucesso!';
                    }else{
                        $_SESSION['erro'] = 'Erro ao salvar o arquivo!';
                    }


                    $_SESSION['msg'] = 'TCC cadastrado com sucesso!';
                }

            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroTCC.php');

        break;
        default:
        break;
    }

}else{
    $_SESSION['erro'] = 'Acesso Invalido';
    header('Location: ../visao/telaLogin.php');
}

?>