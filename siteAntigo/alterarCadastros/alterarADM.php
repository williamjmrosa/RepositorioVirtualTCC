<?php
include_once '../dao/admdao.class.php';
include_once '../Modelo/adm.class.php';

function montarListaAdm($adm){
    if(is_array($adm) && count($adm) > 0){
        foreach ($adm as $v) {
            echo '<tr>
                    <th scope="row">' . $v->email . '</th>
                    <td>' . $v->nome . '</td>
                    <td class="text-center"><a href="'. $v->email . '" class="btn btn-primary alterarAdm w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/adm-controle.php?OP=3&id=' . $v->email . '" class="btn btn-danger excluir w-100">Excluir</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="4">Nenhum Administrador encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar adm
        case 1:
            $admDAO = new AdmDAO();
            $adm = $admDAO->listarAdministradores();

            montarListaAdm($adm);

            break;
        //Buscar Administradores por tipo
        case 2:
            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'nome' || $tipo == 'email'){
                    $admDAO = new AdmDAO();
                    $adm = $admDAO->encontrarAdmPorTipo($busca, $tipo);

                }else{
                    $adm = null;
                }

                montarListaAdm($adm);

            }else{
                header('Location: ../alterarCadastros/alterarADM.php?OP=1');
            }

            break;
        
        // Buscar um Adm
        case 3:

            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_EMAIL);

                $admDAO = new AdmDAO();
                $vitante = $admDAO->encontrarAdmPorEmail($id);

                if(is_array($vitante) && count($vitante) > 0){

                    echo json_encode($vitante);

                }else{
                    $error = array("error" => "Nenhum Administrador encontrado.");
                    echo json_encode($error);
                }
            }else{
                $error = array("error" => "Acesso negado.");
                echo json_encode($error);
            }
            break;

            default:
                header('Location: ../alterarCadastros/alterarADM.php?OP=1');
                break;

    }

}else{
    header('Location: ../alterarCadastros/alterarAdm.php?OP=1');
}

?>