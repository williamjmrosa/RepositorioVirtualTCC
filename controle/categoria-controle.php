<?php
session_start();
include_once '../Modelo/categoria.class.php';
include_once '../util/validacao.class.php';
include_once '../util/padronizacao.class.php';
include_once '../dao/categoriadao.class.php';

if (isset($_GET['OP'])) {
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {

        case 1:

            $erros = array();
            if(!isset($_POST['nomeCategoria'])) {
                $erros[] = 'Campo nome não existe';
            }elseif(empty($_POST['nomeCategoria'])) {
                $erros[] = 'Campo nome em branco';
            }else{
                $nome = filter_var($_POST['nomeCategoria'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if(!isset($_POST['nomeAlternativo'])){
                $erros[] = 'Campo nome alternativo não existe';
            }else{
                foreach($_POST['nomeAlternativo'] as $v) {
                    if(empty($v)){
                        $erros[] = 'Campo nome alternativo em branco';
                    }else{
                        if(!Validacao::validarCategoria($v)){
                            $erros[] = 'Nome alternativo inválido';
                            echo "entrou";
                            break;
                        }else{
                            $nomeAlternativo[] = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);
                        }
                    }
                    
                }
            }

            if(!isset($_POST['eSub'])){
                $erros[] = 'Campo eSub não existe';
            }else{
                $eSub  = filter_var($_POST['eSub'],FILTER_SANITIZE_SPECIAL_CHARS);
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
                for($i = 0; $i < count($nomeAlternativo); $i++){
                    $nomeAlternativo[$i] = Padronizacao::padronizarNome($nomeAlternativo[$i]);
                }
                $categoria->nomeAlternativo = $nomeAlternativo;
                $categoria->eSub = $eSub;
                $categoria->categoriaPrincipal = isset($principal)  ? $principal : NULL;
                print_r($nomeAlternativo);
                echo $eSub . ' convertido ' . $categoria->eSub;
                $cDAO = new CategoriaDAO();
                $cDAO->cadastrarCategoria($categoria);

                $_SESSION['msg'] = 'Categoria Cadastrada!';

            }else{
                $_SESSION['erros'] = serialize($erros);
                print_r($erros);
            }
            header('location: ../visao/telaCadastroCategoria.php');
                break;

        default:
            break;
    }
}
