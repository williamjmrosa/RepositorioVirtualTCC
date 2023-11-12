<?php
include_once '../dao/professordao.class.php';
include_once '../Modelo/professor.class.php';


function montarListaAluno($professores){
    if(is_array($professores) && count($professores) > 0){
        foreach ($professores as $p) {
            echo '<tr>
                    <th scope="row">' . $p->matricula . '</th>
                    <td>' . $p->nome . '</td>
                    <td>' . $p->email . '</td>
                    <td>' . $p->cpf . '</td>
                    <td>' . $p->rg . '</td>
                    <td>' . $p->mostrarStatus() . '</td>
                    <td class="text-center"><a href="'. $p->matricula . '" class="btn btn-primary alterarProfessor w-100" onclick="preencherForm(this,event)">Alterar</a></td>
                    <td class="text-center"><a href="../controle/professor-controle.php?OP=3&id=' . $p->matricula . '" class="btn btn-danger excluir w-100">Mudar Status</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="8">Nenhum Professor encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])){

    $op = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($op) {
        //Listar professores
        case 1:
            $professorDAO = new ProfessorDAO();
            $professores = $professorDAO->listarProfessores();

            montarListaAluno($professores);

            break;

        //Buscar professores por nome
        case 2:

            if(isset($_POST['BUSCA']) && !empty($_POST['BUSCA']) && isset($_POST['TIPO']) && !empty($_POST['TIPO'])){
                
                $busca = filter_var($_POST['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
                $tipo = filter_var($_POST['TIPO'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($tipo == 'nome' || $tipo == 'matricula' || $tipo == 'email' || $tipo == 'cpf' || $tipo == 'rg'){
                    $professorDAO = new ProfessorDAO();
                    $professores = $professorDAO->buscarProfessorPorTipo($busca, $tipo);

                        
                }else{
                    $professores = null;
                }

                montarListaAluno($professores);

            }else{
                header('Location: ../alterarCadastros/alterarProfessor.php?OP=1');
            }

            break;

        //Busca um Aluno
        case 3:
            if(isset($_GET['id'])){
                
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

                $professorDAO = new ProfessorDAO();
                $professores = $professorDAO->buscarProfessorPorMatricula($id);

                if(is_array($professores)){
                    echo json_encode($professores);
                }else{
                    $error = array("error" => "Nenhum Professor encontrado.");
                    echo json_encode($error);
                }

            }else{
                $error = array("error" => "Acesso negado.");
                    echo json_encode($error);
            }

            break;
        default:
            header('Location: ../alterarCadastros/alterarProfessor.php?OP=1');
            break;
            
    }

}else{
    header('Location: ../alterarCadastros/alterarProfessor.php?OP=1');
}

?>