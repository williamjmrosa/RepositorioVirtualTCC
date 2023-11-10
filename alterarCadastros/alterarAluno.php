<?php
include_once '../Modelo/aluno.class.php';
include_once '../dao/alunodao.class.php';

function montarListaAluno($alunos){
    if(is_array($alunos) && count($alunos) > 0){
        foreach ($alunos as $a) {
            echo '<tr>
                    <th scope="row">' . $a->matricula . '</th>
                    <td>' . $a->nome . '</td>
                    <td>' . $a->email . '</td>
                    <td>' . $a->cpf . '</td>
                    <td>' . $a->rg . '</td>
                    <td>' . $a->mostrarStatus() . '</td>
                    <td class="text-center"><a href="'. $a->matricula . '" class="btn btn-primary alterarAluno w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/aluno-controle.php?OP=3&id=' . $a->matricula . '" class="btn btn-danger excluir w-100">Mudar Status</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="8">Nenhum Aluno encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){

    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($op) {
        //Listar alunos
        case 1:
            $alunoDAO = new AlunoDAO();
            $alunos = $alunoDAO->listarAlunos();

            montarListaAluno($alunos);

            break;

        //Buscar alunos por nome
        case 2:

            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'nome' || $tipo == 'matricula' || $tipo == 'email' || $tipo == 'cpf' || $tipo == 'rg'){
                    $alunoDAO = new AlunoDAO();
                    $alunos = $alunoDAO->buscarAlunoPorTipo($busca, $tipo);

                        
                }else{
                    $alunos = null;
                }

                montarListaAluno($alunos);

            }else{
                header('Location: ../alterarCadastros/alterarAluno.php?OP=1');
            }

            break;

        //Busca um Aluno
        case 3:
            if(isset($_GET['id'])){
                
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $alunoDAO = new AlunoDAO();
                $alunos = $alunoDAO->buscarAlunoPorMatricula($id);

                if(is_array($alunos)){
                    echo json_encode($alunos);
                }else{
                    $error = array("error" => "Nenhum Aluno encontrado.");
                    echo json_encode($error);
                }

            }else{
                $error = array("error" => "Acesso negado.");
                    echo json_encode($error);
            }

            break;
        default:
            header('Location: ../alterarCadastros/alterarAluno.php?OP=1');
            break;
            
    }

}

?>