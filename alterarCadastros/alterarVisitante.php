<?php
include_once '../dao/visitantedao.class.php';
include_once '../Modelo/visitante.class.php';

function montarListaVisitante($visitantes){
    if(is_array($visitantes) && count($visitantes) > 0){
        foreach ($visitantes as $v) {
            echo '<tr>
                    <th scope="row">' . $v->email . '</th>
                    <td>' . $v->nome . '</td>
                    <td class="text-center"><a href="'. $v->email . '" class="btn btn-primary alterarVisitante w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/visitante-controle.php?OP=3&id=' . $v->email . '" class="btn btn-danger excluir w-100">Excluir</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="8">Nenhum Visitante encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar Visitantes
        case 1:
            $visitanteDAO = new VisitanteDAO();
            $visitantes = $visitanteDAO->listarVisitantes();

            montarListaVisitante($visitantes);

            break;
        //Buscar Visitantes por nome
        case 2:

            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'nome' || $tipo == 'email'){
                    $visitanteDAO = new VisitanteDAO();
                    $visitantes = $visitanteDAO->encontrarVisitantePorTipo($busca, $tipo);

                }else{
                    $visitantes = null;
                }

                montarListaVisitante($visitantes);

            }else{
                header('Location: ../alterarCadastros/alterarVisitante.php?OP=1');
            }

            break;
        
        // Buscar um visitante
        case 3:

            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_EMAIL);

                $visitanteDAO = new VisitanteDAO();
                $vitante = $visitanteDAO->encontrarVisitantePorEmail($id);

                if(is_array($vitante) && count($vitante) > 0){

                    echo json_encode($vitante);

                }else{
                    $error = array("error" => "Nenhum Visitante encontrado.");
                    echo json_encode($error);
                }
            }else{
                $error = array("error" => "Acesso negado.");
                echo json_encode($error);
            }
            break;

            default:
                header('Location: ../alterarCadastros/alterarVisitante.php?OP=1');
                break;

    }

}else{
    header('Location: ../alterarCadastros/alterarVisitante.php?OP=1');
}

?>