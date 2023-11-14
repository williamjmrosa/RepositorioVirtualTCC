<?php
include_once '../Modelo/tcc.class.php';
include_once '../dao/tccdao.class.php';

function montarListaTCC($tccs){
    if(is_array($tccs) && count($tccs) > 0){
        foreach ($tccs as $tcc) {
            echo '<tr>
                    <th scope="row">' . $tcc->idTCC . '</th>
                    <td>' . $tcc->titulo . '</td>
                    <td>' . $tcc->aluno->nome . '</td>
                    <td class="text-center"><a href="'. $tcc->idTCC . '" class="btn btn-primary alterarTCC w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/tcc-controle.php?OP=3&id=' . $tcc->idTCC . '" class="btn btn-danger excluir w-100">Excluir</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="5">Nenhum TCC encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){
    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch($op){
        // Listar TCC
        case 1:
            $tccDAO = new TCCDAO();
            $tccs = $tccDAO->listarTodosTCC();
            montarListaTCC($tccs);
            
            break;
        // Buscar TCC por tipo
        case 2:
            
            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'idTCC' || $tipo == 'nome' || $tipo == 'titulo'){
                    $tccDAO = new TCCDAO();
                    $tccs = $tccDAO->buscarTCCPorTipo($busca, $tipo);
    
                }else{
                    $tccs = null;
                }

                montarListaTCC($tccs);

            }else{
                header('Location: ../alterarCadastros/alterarTCC.php?OP=1');
            }

            break;
            // Buscar um TCC
        case 3:
            
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $tccDAO = new TCCDAO();
                $tcc = $tccDAO->buscarTCCID($id,true);

                if(is_array($tcc) && count($tcc) > 0){
                    echo json_encode($tcc);
                }else{
                    $error = array("error" => "TCC não encontrado");
                    echo json_encode($error);
                }
            }else{
                $error = array("error" => "Acesso negado");
                echo json_encode($error);
            }

            break;
        default:
            header('Location: ../alterarCadastros/alterarTCC.php?OP=1');
            break;

    }
}
?>