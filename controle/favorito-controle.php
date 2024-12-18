<?php
session_start();
include_once '../dao/favoritodao.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';
function verSeEAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

if(isset($_GET['OP']) && isset($_SESSION['usuario'])){

    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    $user = unserialize($_SESSION['usuario']);
    $tipo = get_class($user);

    switch($OP){
        //Cadastrar Favorito Aluno
        case 1:
            if($tipo != 'Aluno' && $tipo != 'Professor' && $tipo != 'Visitante'){
                echo json_encode("Efetue o login como Aluno, Professor ou Visitante para favoritar!");
                break;    
            }

            if(isset($_POST['idTCC'])){
                $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);
                     
                $fDAO = new FavoritoDAO();
                if($tipo == 'Aluno' || $tipo == 'Professor'){
                    $idUsuario = $user->matricula;
                }elseif($tipo == 'Visitante'){
                    $idUsuario = $user->email;
                }elseif($tipo == 'Bibliotecario' || $tipo == 'Adm'){
                    echo json_encode("Este usuario não pode favoritar");
                    break;
                }
                if($fDAO->cadastrarFavorito($idUsuario, $idTCC, $tipo)){
                    echo json_encode("TCC adicionado aos favoritos");
                }

            }else{
                echo json_encode("Erro ao favoritar");
            }

            break;
        //Remover Favorito
        case 2:
            if(isset($_POST['idTCC'])){
                $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);

                $fDAO = new FavoritoDAO();
                if($tipo == 'Aluno' || $tipo == 'Professor'){
                    $idUsuario = $user->matricula;
                }elseif($tipo == 'Visitante'){
                    $idUsuario = $user->email;
                }elseif($tipo == 'Bibliotecario' || $tipo == 'Adm'){
                    echo json_encode("Este usuario não pode favoritar");
                    break;
                }
                if($fDAO->removerFavorito($idUsuario, $idTCC, $tipo)){
                    echo json_encode("TCC removido dos favoritos");
                }

            }else{
                echo json_encode("Erro ao desfavoritar");
            }
            break;
        default:
            if(verSeEAjax()){
                echo json_encode("Opeção inválida");
            }else{
                header('location:../visao/index.php');    
            }
            break;

    }

}else{
    if(verSeEAjax()){
        echo json_encode("Deve Logar para favoritar");
    }else{
        header('location:../visao/index.php');
    }
}

?>