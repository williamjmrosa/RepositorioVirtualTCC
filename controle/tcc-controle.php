<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
include_once '../util/padronizacao.class.php';
include_once '../Modelo/curso.class.php';
include_once '../Modelo/campus.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';
include_once '../Modelo/categoria.class.php';
include_once '../util/validacao.class.php';

function diretorioExiste($dir){
    if(is_dir($dir)){
        return true;
    }else{
        return false;
    }
}

function excluirDiretorio($dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*'); // Obtém todos os arquivos e diretórios dentro do diretório
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Exclui o arquivo
            } elseif (is_dir($file)) {
                excluirDiretorio($file); // Chama recursivamente a função para excluir subdiretórios
            }
        }
        rmdir($dir); // Exclui o diretório vazio 
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

function alterarArquivo($file, $id, $localPDFOriginal) {
    if($file != null){

        $raiz = '../TCC/' . $id;
        $destino = $raiz . '/' . $file['name'];

        if (diretorioExiste($raiz) == false) {
            mkdir($raiz, 0777, true);
        }

        
        $tccDAO = new TCCDAO();
        if (move_uploaded_file($file['tmp_name'], $destino) && $tccDAO->updateLocalPDF($id, $destino)) {
            // Deleta o arquivo antigo, se existir
            $arquivoAntigo = $localPDFOriginal;
            if (file_exists($arquivoAntigo)) {
                unlink($arquivoAntigo);
            }
            // Deleta a capa antiga, se existir
            $capaAntiga = $raiz . '/capa.jpg';
            if (file_exists($capaAntiga)) {
                unlink($capaAntiga);
            }
            return true;
        } else {
            return false;
        }

    }else{
        return true;
    }
}


if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    if(isset($_SESSION['usuario'])){
        $user = unserialize($_SESSION['usuario']);
        $tipo = get_class($user);
    }else{
        $tipo = null;
    }

    switch($OP){
        //Cadastrar TCC
        case 1:            
            $erros = array();                        

            if($tipo != 'Bibliotecario' && $tipo != 'Adm'){
                $erros[] = 'Efetue o Login como Adm ou Bibliotecário para realizar esta operação no sistema!';
                $_SESSION['erros'] = serialize($erros);
                header('Location: ../index.php');
                break;
            }

            if(!isset($_POST['titulo'])){
                $erros[] = 'Campo Título não existe!';
            }elseif($_POST['titulo'] == ""){
                $erros[] = 'Campo Título em branco!';
            }elseif(strlen($_POST['titulo']) < 10){
                $erros[] = 'Titulo deve ter no minimo 10 caracteres!';
            }else if(strlen($_POST['titulo']) > 100){
                $erros[] = 'Título deve ter no maximo 100 caracteres!';
            }else{
                $titulo = filter_var($_POST['titulo'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($titulo,100)){
                    $erros[] = 'Título deve ter no maximo 100 caracteres!';
                }
            }

            if(!isset($_POST['autor'])){
                $erros[] = 'Campo Autor não selecionado!';
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
                $erros[] = 'Campo Curso não existe!';
            }elseif($_POST['curso'] == ''){
                $erros[] = 'Campo Curso em branco!';
            }else{
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['descricao'])){
                $erros[] = 'Campo Descrição não existe!';
            }elseif($_POST['descricao'] == ""){
                $erros[] = 'Campo Descrição em branco!';
            }elseif(strlen($_POST['descricao']) < 10){
                $erros[] = 'Descrição deve ter no minimo 10 caracteres!';
            }elseif(strlen($_POST['descricao']) > 600){
                $erros[] = 'Descrição deve ter no maximo 600 caracteres!';
            }else{
                $descricao = filter_var($_POST['descricao'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($descricao,600)){
                    $erros[] = 'Descrição deve ter no maximo 600 caracteres!';
                }
            }

            if(!isset($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Campo Local PDF não existe!';
            }elseif(empty($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Campo Local PDF em branco!';
            }elseif(!validacao::validarPDF($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Arquivo não é um PDF!';
            }else{
                $localPDF = $_FILES['localPDF'];
            }

            if(!isset($_POST['categorias'])){
                $erros[] = 'Campo Categorias não selecionado!';
            }elseif($_POST['categorias'] == ""){
                $erros[] = 'Nenhum categoria foi marcada!';
            }else{
                $categorias = array();
                foreach($_POST['categorias'] as $categoria){
                    $categorias[] = filter_var($categoria, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(!isset($_POST['orientador'])){
                $erros[] = 'Campo Orientador não selecionado!';
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

                if($tcc != false){
                    if(salvarArquivo($localPDF,$tcc->idTCC)){
                        $_SESSION['msg'] = 'TCC cadastrado com sucesso!';
                    }else{
                        $erros[] = 'Erro ao salvar o arquivo!';
                        if(isset($_SESSION['erros'])){
                            $erros = unserialize($_SESSION['erros']);
                            $erros[] = 'Erro ao salvar o arquivo!';
                            $_SESSION['erros'] = serialize($erros);
                        }
                    }

                }else{
                    $erros[] = 'Erro ao cadastrar TCC!';
                    if(isset($_SESSION['erros'])){
                        $erros = unserialize($_SESSION['erros']);
                        $erros[] = 'Erro ao cadastrar TCC!';
                        $_SESSION['erros'] = serialize($erros);
                    }
                }

            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroTCC.php');

        break;
        //Alterar TCC
        case 2:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = serialize($erros);
                header('Location: ../index.php');
                break;
            }

            if(isset($_POST['idTCC']) && filter_var($_POST['idTCC'], FILTER_VALIDATE_INT) && $_POST['idTCC'] > 0){
                $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $erros[] = 'Campo ID TCC não existe ou é inválido!';
            }

            if(!isset($_POST['localPDFOriginal']) || empty($_POST['localPDFOriginal'])){
                $erros[] = 'Campo Local PDF não existe ou esta em branco!';
            }else{
                $localPDFOriginal = $_POST['localPDFOriginal'];
            }                     

            if(!isset($_POST['titulo'])){
                $erros[] = 'Campo Título não existe!';
            }elseif($_POST['titulo'] == ""){
                $erros[] = 'Campo Título em branco!';
            }elseif(strlen($_POST['titulo']) < 10){
                $erros[] = 'Titulo deve ter no minimo 10 caracteres!';
            }else if(strlen($_POST['titulo']) > 100){
                $erros[] = 'Título deve ter no maximo 100 caracteres!';
            }else{
                $titulo = filter_var($_POST['titulo'],FILTER_SANITIZE_SPECIAL_CHARS);
                if(!Validacao::validarTamanho($titulo,100)){
                    $erros[] = 'Título deve ter no maximo 100 caracteres!';
                }
            }

            if(!isset($_POST['autor'])){
                $erros[] = 'Campo Autor não selecionado!';
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
                $erros[] = 'Campo Curso não existe!';
            }elseif($_POST['curso'] == ''){
                $erros[] = 'Campo Curso em branco!';
            }else{
                $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }

            if(!isset($_POST['descricao'])){
                $erros[] = 'Campo Descrição não existe!';
            }elseif($_POST['descricao'] == ""){
                $erros[] = 'Campo Descrição em branco!';
            }elseif(strlen($_POST['descricao']) < 10){
                $erros[] = 'Descrição deve ter no minimo 10 caracteres!';
            }elseif(strlen($_POST['descricao']) > 600){
                $erros[] = 'Descrição deve ter no maximo 600 caracteres!';
            }else{
                $descricao = filter_var($_POST['descricao'],FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Campo Local PDF não existe!';
            }elseif(empty($_FILES['localPDF']['tmp_name'])){
                $localPDF = null;
            }elseif(!validacao::validarPDF($_FILES['localPDF']['tmp_name'])){
                $erros[] = 'Arquivo não é um PDF!';
            }else{
                $localPDF = $_FILES['localPDF'];
            }

            if(!isset($_POST['categorias'])){
                $erros[] = 'Campo Categorias não selecionado!';
            }elseif($_POST['categorias'] == ""){
                $erros[] = 'Nenhum categoria foi marcada!';
            }else{
                $categorias = array();
                foreach($_POST['categorias'] as $categoria){
                    $categorias[] = filter_var($categoria, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(!isset($_POST['orientador'])){
                $erros[] = 'Campo Orientador não selecionado!';
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
                $tcc->idTCC = $idTCC;
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
                $tcc->localPDF = $localPDF;

                $tccDAO = new TCCDAO();

                if($tccDAO->alterarTCC($tcc) && alterarArquivo($localPDF,$tcc->idTCC,$localPDFOriginal)){
                    $_SESSION['msg'] = 'TCC alterado com sucesso!';
                    
                }else{
                    $erros[] = 'Erro ao alterar TCC!';
                }
                
                if(isset($_SESSION['erros']) && count($erros) > 0){
                    $erros = array_merge(unserialize($_SESSION['erros']), $erros);
                    $erros[] = 'Erro ao alterar TCC!';
                    $_SESSION['erros'] = serialize($erros);
                }else if(count($erros) > 0){
                    $_SESSION['erros'] = serialize($erros);

                }

            }else{
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/telaCadastroTCC.php');

            break;
        //Excluir TCC
        case 3:
            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = serialize($erros);
                header('Location: ../index.php');
                break;
            }

            if(isset($_GET['id'])){
                $idTCC = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $tccDAO = new TCCDAO();
                if($tccDAO->deletarCategorias($idTCC) && $tccDAO->deletarOrientador($idTCC) && $tccDAO->deletarTCC($idTCC)){
                    excluirDiretorio("../TCC/$idTCC");
                    $_SESSION['msg'] = 'TCC excluído com sucesso!';
                }else{
                    $_SESSION['erro'] = 'Erro ao excluir TCC!';
                }

            }else{
                $_SESSION['erro'] = 'Acesso Invalido';
            }

            header('Location: ../visao/telaCadastroTCC.php');

            break;
        // Baixar TCC
        case 4:
            $tccDAO = new TCCDAO();

            if(isset($_POST['idTCC'])){
                $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $_SESSION['erro'] = 'Acesso Invalido';
                header('Location: ../visao/index.php');
            }
            
            $tcc = $tccDAO->buscarTCCID($idTCC);

            // Caminho do arquivo PDF no servidor
            $caminho_arquivo = $tcc['localPDF'];
            
            // Define o nome do arquivo para download
            $nome_arquivo = $tcc['titulo'] . '.pdf';
            
            // Define o tipo de conteúdo como PDF
            header('Content-Type: application/pdf');
            
            // Define o cabeçalho para download
            header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
            
            // Lê e envia o conteúdo do arquivo PDF
            readfile($caminho_arquivo);
            
            // Encerra o script
            header('Location: ../visao/lerTCC.php?TCC=' .$idTCC);
            break;
        
        // Filtrar TCC
        case 5:
            $filtros = array();
            if(isset($_POST['categorias'])){
                $categorias = array();
                foreach($_POST['categorias'] as $categoria){
                    $categorias[] = filter_var($categoria, FILTER_SANITIZE_NUMBER_INT);
                }
                $filtros['categorias'] = $categorias;
            }else{
                $categorias = null;
            }

            if(isset($_POST['orientador']) && !empty($_POST['orientador'])){
                $filtros['orientador'] = $orientador = filter_var($_POST['orientador'], FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $orientador = null;
            }

            if(isset($_POST['curso'])){
                $filtros['curso'] = $curso = filter_var($_POST['curso'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $curso = null;
            }

            if(isset($_POST['campus'])){
                $filtros['campus'] = $campus = filter_var($_POST['campus'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $campus = null;
            }

            if(isset($_POST['autor']) && !empty($_POST['autor'])){
                $filtros['autor'] = $autor = filter_var($_POST['autor'], FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $autor = null;
            }

            if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
                $filtros['titulo'] = $titulo = filter_var($_POST['titulo'],FILTER_SANITIZE_SPECIAL_CHARS);
            }else{
                $titulo = null;
            }

            if(isset($_POST['paginaAtual'])){
                $filtros['paginaAtual'] = $paginaAtual = filter_var($_POST['paginaAtual'], FILTER_SANITIZE_NUMBER_INT);
            }else{
                $filtros['paginaAtual'] = $paginaAtual = 1;
            }
            
            if(count($filtros) > 1){

            $tccDAO = new TCCDAO();
            $tccs = $tccDAO->buscarTCCs($curso, $campus, $titulo, $categorias, $autor, $orientador, $paginaAtual);
            
            $_SESSION['tccs'] = serialize($tccs);
            $_SESSION['filtros'] = serialize($filtros);

            }

            header('Location: ../visao/index.php');

            break;

        default:
        break;
    }

}else{
    $_SESSION['erro'] = 'Acesso Invalido';
    header('Location: ../visao/index.php');
}

?>