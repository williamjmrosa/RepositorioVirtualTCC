<?php
session_start();
include_once '../dao/tccdao.class.php';
include_once '../Modelo/tcc.class.php';
function verSeEAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}
if(isset($_SESSION['usuario']) && verSeEAjax()){

    if(isset($_POST['idTCC'])){
        $idTCC = filter_var($_POST['idTCC'], FILTER_SANITIZE_NUMBER_INT);

        $tccDAO = new TCCDAO();

        $tcc = $tccDAO->buscarTCCID($idTCC);
        if($tcc != null ){
            $array['sucesso'] = $tcc;
            echo json_encode($array);
        }else{
            $erros['erro'] = "Erro ao encontrar tcc";
            echo json_encode($erros);
        }
    }else{
        $erros['erro'] = "Necessario estar logado para acessar esta pagina";
        echo json_encode($erros);
    } 

}else{
    if(verSeEAjax()){
        $erros['erro'] = "Necessario estar logado para acessar esta pagina";
        echo json_encode($erros);
    }else{
        $erros[] = "Necessario estar logado para acessar esta pagina";
        $_SESSION['erros'] = $erros;
        header('Location: ../visao/tccMarcado.php');
    }
}
?>