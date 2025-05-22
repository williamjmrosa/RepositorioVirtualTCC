<?php
include_once '../dao/bibliotecariodao.class.php';
include_once '../Modelo/bibliotecario.class.php';

function montarListabibliotecario($bibliotecarios){
    if(is_array($bibliotecarios) && count($bibliotecarios) > 0){
        foreach ($bibliotecarios as $v) {
            echo '<tr>
                    <th scope="row">' . $v->email . '</th>
                    <td>' . $v->nome . '</td>
                    <td class="text-center"><a href="'. $v->email . '" class="btn btn-primary alterarbibliotecario w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/bibliotecario-controle.php?OP=3&id=' . $v->email . '" class="btn btn-danger excluir w-100">Excluir</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="4">Nenhum Bibliotecário encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar bibliotecarios
        case 1:
            $bibliotecarioDAO = new bibliotecarioDAO();
            $bibliotecarios = $bibliotecarioDAO->listarBibliotecarios();

            montarListabibliotecario($bibliotecarios);

            break;
        //Buscar bibliotecarios por nome
        case 2:

            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'nome' || $tipo == 'email'){
                    $bibliotecarioDAO = new bibliotecarioDAO();
                    $bibliotecarios = $bibliotecarioDAO->encontrarbibliotecarioPorTipo($busca, $tipo);

                }else{
                    $bibliotecarios = null;
                }

                montarListabibliotecario($bibliotecarios);

            }else{
                header('Location: ../alterarCadastros/alterarbibliotecario.php?OP=1');
            }

            break;
        
        // Buscar um bibliotecario
        case 3:

            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_EMAIL);

                $bibliotecarioDAO = new bibliotecarioDAO();
                $vitante = $bibliotecarioDAO->encontrarbibliotecarioPorEmail($id);

                if(is_array($vitante) && count($vitante) > 0){

                    echo json_encode($vitante);

                }else{
                    $error = array("error" => "Nenhum bibliotecario encontrado.");
                    echo json_encode($error);
                }
            }else{
                $error = array("error" => "Acesso negado.");
                echo json_encode($error);
            }
            break;

            default:
                header('Location: ../alterarCadastros/alterarbibliotecario.php?OP=1');
                break;

    }

}else{
    header('Location: ../alterarCadastros/alterarbibliotecario.php?OP=1');
}

?>