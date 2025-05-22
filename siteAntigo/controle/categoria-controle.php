<?php
session_start();
include_once '../Modelo/categoria.class.php';
include_once '../util/validacao.class.php';
include_once '../util/padronizacao.class.php';
include_once '../dao/categoriadao.class.php';
include_once '../Modelo/adm.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/aluno.class.php';

if (isset($_GET['OP']) && isset($_SESSION['usuario'])) {
    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {

        //Cadastrar Categoria
        case 1:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

                if(!isset($_POST['nomeCategoria'])) {
                    $erros[] = 'Campo nome não existe';
                }elseif(empty($_POST['nomeCategoria'])) {
                    $erros[] = 'Campo nome em branco';
                }else{
                    $nome = filter_var($_POST['nomeCategoria'], FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!Validacao::validarTamanho($nome,60)){
                        $erros[] = 'Nome categoria muito grande (max. 60 caracteres)';
                    }elseif(!Validacao::validarCategoria($nome)){
                        $erros[] = 'Nome categoria inválido';
                    }
                }

                if(isset($_POST['nomeAlternativo'])){
                    foreach($_POST['nomeAlternativo'] as $v) {
                        $nomeAlternativo = array();
                        if(!empty($v)){
                            if(!Validacao::validarCategoria($v)){
                                $erros[] = 'Nome alternativo inválido';
                                break;
                            }else if (!Validacao::validarTamanho($v,60)){
                                $erros[] = 'Nome alternativo muito grande (max. 60 caracteres)'
                                ;break;
                            }else{
                                $nomeAlternativo[] = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);
                            }
                        }
                        
                    }
                }

                if(!isset($_POST['eSub'])){
                    $erros[] = 'Campo eSub não existe';
                }else{
                    $eSub  = filter_var($_POST['eSub'],FILTER_VALIDATE_BOOLEAN);
                }

                if($eSub == 'true' && isset($_POST['principal']) && empty($_POST['principal'])){
                    $erros[] = 'Nenhuma categoria selecionada como principal';
                }elseif(!isset($_POST['principal']) && $eSub == 'true'){
                    $erros[] = 'Campo categoria princial não existe';
                }else if($eSub == 'true'){
                    $principal = filter_var($_POST['principal'],FILTER_SANITIZE_NUMBER_INT);
                }
                
                if(count($erros) == 0){
                    $categoria = new Categoria();
                    $categoria->nome = Padronizacao::padronizarNome($nome);
                    if(!empty($nomeAlternativo)){
                        for($i = 0; $i < count($nomeAlternativo); $i++){
                            $nomeAlternativo[$i] = Padronizacao::padronizarNome($nomeAlternativo[$i]);
                        }
                    }
                    $categoria->nomeAlternativo = $nomeAlternativo;
                    $categoria->eSub = $eSub;
                    $categoria->categoriaPrincipal = isset($principal)  ? $principal : NULL;
                    /*print_r($nomeAlternativo);
                    echo $eSub . ' convertido ' . $categoria->eSub; **/
                    $cDAO = new CategoriaDAO();
                    $cDAO->cadastrarCategoria($categoria);

                    $_SESSION['msg'] = 'Categoria Cadastrada!';

                }else{
                    $_SESSION['erros'] = serialize($erros);
                    print_r($erros);
                }

                header('location: ../visao/telaCadastroCategoria.php');
            }
            
            
            
            break;
        //Alterar Categoria
        case 2:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

                if(!isset($_POST['id'])){
                    $erros[] = 'Campo id não existe';
                }elseif(empty($_POST['id'])){
                    $erros[] = 'Campo id em branco';
                }else{
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                }

                if(!isset($_POST['nomeCategoria'])){
                    $erros[] = 'Campo nome não existe';
                }elseif(empty($_POST['nomeCategoria'])) {
                    $erros[] = 'Campo nome em branco';
                }else{
                    $nome = filter_var($_POST['nomeCategoria'], FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!Validacao::validarTamanho($nome,60)){
                        $erros[] = 'Nome categoria muito grande (max. 60 caracteres)';
                    }elseif(!Validacao::validarCategoria($nome)){
                        $erros[] = 'Nome categoria inválido';
                    }
                }

                if(isset($_POST['nomeAlternativo'])){
                    $nomeAlternativo = array();
                    foreach($_POST['nomeAlternativo'] as $v) {
                        if(!empty($v)){
                            if(!Validacao::validarCategoria($v)){
                                $erros[] = 'Nome alternativo inválido';
                                break;
                            }else if (!Validacao::validarTamanho($v,60)){
                                $erros[] = 'Nome alternativo muito grande (max. 60 caracteres)'
                                ;break;
                            }else{
                                $nomeAlternativo[] = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);
                            }
                        }
                        
                    }
                }

                if(!isset($_POST['eSub'])){
                    $erros[] = 'Campo eSub não existe';
                }else{
                    $eSub  = filter_var($_POST['eSub'],FILTER_VALIDATE_BOOLEAN);
                }

                if($eSub == 'true' && isset($_POST['principal']) && empty($_POST['principal'])){
                    $erros[] = 'Nenhuma categoria selecionada como principal';
                }elseif(!isset($_POST['principal']) && $eSub == 'true'){
                    $erros[] = 'Campo categoria princial não existe';
                }else if($eSub == 'true'){
                    $principal = filter_var($_POST['principal'],FILTER_SANITIZE_NUMBER_INT);
                }
                
                if(count($erros) == 0){
                    $categoria = new Categoria();
                    $categoria->idCategoria = $id;
                    $categoria->nome = Padronizacao::padronizarNome($nome);
                    if(!empty($nomeAlternativo)){
                        for($i = 0; $i < count($nomeAlternativo); $i++){
                            $nomeAlternativo[$i] = Padronizacao::padronizarNome($nomeAlternativo[$i]);
                        }
                    }
                    $categoria->nomeAlternativo = $nomeAlternativo;
                    $categoria->eSub = $eSub;
                    $categoria->categoriaPrincipal = isset($principal)  ? $principal : NULL;
                    $cDAO = new CategoriaDAO();
                    $cDAO->alterarCategoria($categoria);

                }else{
                    $_SESSION['erros'] = serialize($erros);
                }

                $_SESSION['msg'] = 'Categoria Alterada!';
                header('location: ../visao/telaCadastroCategoria.php');

            }
            break;
        //Alterar NomeAlternativo
        case 3:

            $erros = array();

            if($tipo != 'Adm' && $tipo != 'Bibliotecario'){
                $erros[] = "Efetue o login como Adm ou Bibliotecário para acessar o sistema!";
                $_SESSION['erros'] = $erros;
                header('Location: ../index.php');
            }else{

                if(!isset($_POST['id'])){
                    $erros[] = 'Campo id não existe';
                }elseif(empty($_POST['id'])){
                    $erros[] = 'Campo id em branco';
                }else{
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                }

                if(!isset($_POST['nomeAlternativo'])){
                    $erros[] = 'Campo nome Alternativo não existe';
                }elseif(empty($_POST['nomeAlternativo'])){
                    $erros[] = 'Campo nome Alternativo em branco';
                }else{
                    $nomeAlternativo = filter_var($_POST['nomeAlternativo'], FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!Validacao::validarTamanho($nomeAlternativo,60)){
                        $erros[] = 'Nome alternativo muito grande (max. 60 caracteres)';
                    }
                    elseif(!Validacao::validarCategoria($nomeAlternativo)){
                        $erros[] = 'Nome alternativo inválido';
                    }
                    
                }
                if(count($erros) == 0){
                    $cDAO = new CategoriaDAO();
                    if($cDAO->alterarNomeAlternativo($nomeAlternativo, $id) == true){
                        $msg = array("msg" => "Nome alternativo alterado com sucesso");
                        echo json_encode($msg);
                    }else{
                        $erros[] = "Erro ao alterar nome alternativo";
                        echo json_encode($erros);
                        
                    }

                }else{
                    //$_SESSION['erros'] = serialize($erros);
                    echo json_encode($erros);
                }

            }
            break;
        //Excluir Categoria
        case 4:

            $erros = array();

            if($tipo != "Adm" && $tipo != "Bibliotecario"){
                $erros[] = 'Efetue o Login como Adm ou Bibliotecário para realizar esta operação no sistema!';
                $_SESSION['erros'] = serialize($erros);
                header('Location: ../visao/telaLogin.php');
            }else{
                
                if(!isset($_GET['id'])){
                    $erros[] = 'Campo id não existe';
                    $_SESSION['erros'] = serialize($erros);
                }elseif(empty($_GET['id'])){
                    $erros[] = 'Campo id em branco';
                    $_SESSION['erros'] = serialize($erros);
                }else{
                    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                
                    $cDAO = new CategoriaDAO();
                    if($cDAO->excluirCategoria($id)){
                        $_SESSION['msg'] = "Categoria excluída com sucesso";
                        
                    }else{
                        $erros[] = "Erro ao excluir categoria";
                        $_SESSION['erros'] = serialize($erros);
                    
                    }
                }


                header('Location: ../visao/telaCadastroCategoria.php');
            }
            break;
        //Excluir NomeAlternativo
        case 5:

            $erros = array();

            if($tipo != "Adm" && $tipo != "Bibliotecario"){
                $erros[] = 'Efetue o Login como Adm ou Bibliotecário para realizar esta operação no sistema!';
                $_SESSION['erros'] = serialize($erros);
                header('Location: ../visao/telaLogin.php');
            }else{
                
                if(!isset($_GET['id'])){
                    $erros[] = 'Campo id não existe';
                    $_SESSION['erros'] = serialize($erros);
                }elseif(empty($_GET['id'])){
                    $erros[] = 'Campo id em branco';
                    $_SESSION['erros'] = serialize($erros);
                }else{
                    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                
                    $cDAO = new CategoriaDAO();
                    if($cDAO->excluirNomeAlternativo($id)){
                        $_SESSION['msg'] = "Nome alternativo excluído com sucesso";
                        
                    }else{
                        $erros[] = "Erro ao excluir nome alternativo";
                        $_SESSION['erros'] = serialize($erros);
                    
                    }
                }

                header('Location: ../visao/telaCadastroCategoria.php');
            }

            break;
        default:
            break;
    }
}else{
    $erros[] = "Deve estar logado";
    $_SESSION['erros'] = serialize($erros);
    header('Location: ../visao/telaLogin.php');
}
