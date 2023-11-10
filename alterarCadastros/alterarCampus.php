<?php
include_once '../Modelo/campus.class.php';
include_once '../dao/campusdao.class.php';
function montarListaCampus($campus){
    if (is_array($campus) && count($campus) > 0) {
        foreach ($campus as $c) {
            $ativo = $c->ativo == null ? 'Ativo' : 'Inativo';
            $ativar = $c->ativo == null ? 'Desativar' : 'Ativar';
            echo '<tr>
                    <th scope="row">' . $c->idCampus . '</th>
                    <td>' . $c->nome . '</td>
                    <td>' . $ativo . '</td>
                    <td class="text-center"><a href="'. $c->idCampus . '" class="btn btn-primary alterarCampus w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/campus-controle.php?OP=3&id=' . $c->idCampus . '" class="btn btn-danger excluir w-100">'. $ativar.'</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="5">Nenhum Campus encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])) {
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar Campus
        case 1:
                $cDAO = new CampusDAO();
                $campus = $cDAO->listarCampus();

                montarListaCampus($campus);
            break;
        //Buscar Campus por nome
        case 2:
            if (isset($_GET['BUSCA'])) {
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

                $cDAO = new CampusDAO();
                $campus = $cDAO->buscarCampusPorNome($busca);

                montarListaCampus($campus);
            }else{
                header('Location: ../alterarCadastros/alterarCampus.php?OP=1');
            }
            break;
        //Alterar Campus
        case 3:
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $cDAO = new CampusDAO();
                $campus = $cDAO->buscarCampusPorId($id);
                //print_r($campus);
                if(is_array($campus)){
                    echo json_encode($campus);
                }else{
                    $error = array("error" => "Nenhum campus encontrado.");
                    echo json_encode($error);
                }

            }

            break;
        // Desativar/Ativar Campus
        case 4:
            $erros = array();
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $cDAO = new CampusDAO();
                $campus = $cDAO->alterarStatusCampus($id);

                if($campus){
                    $_SESSION['msg'] = "Campus ativado/desativado com sucesso!";
                }else{
                    $erros[] = "Erro ao ativar/desativar campus!";
                    $_SESSION['erros'] = serialize($erros);
                }

            }else{
                $erros[] = "Nenhum campus selecionado!";
                $_SESSION['erros'] = serialize($erros);
            }

            header('Location: ../visao/cadastroCampus.php');

        default:
            header('Location: ../alterarCadastros/alterarCampus.php?OP=1');
            break;

    }
}else{
    header('Location: ../alterarCadastros/alterarCampus.php?OP=1');
}

?>