<?php
include_once '../Modelo/curso.class.php';
include_once '../dao/cursodao.class.php';

function montarListaCurso($cursos){
    if (is_array($cursos) && count($cursos) > 0) {
        foreach ($cursos as $c) {
            $ativo = $c->ativo == null ? 'Ativo' : 'Inativo';
            $ativar = $c->ativo == null ? 'Desativar' : 'Ativar';
            echo '<tr>
                    <th scope="row">' . $c->idCurso . '</th>
                        <td>' . $c->nome . '</td>
                        <td class="text-center">' . $c->mostrarEnsino() . '</td>
                        <td class="text-center">' . $ativo . '</td>
                        <td class="text-center"><a href="' . $c->idCurso . '" class="btn btn-primary alterarCurso" onclick="preencherForm(this,event)">Alterar</a></td>
                        <td class="text-center"><a href="../controle/curso-controle.php?OP=3&id=' . $c->idCurso . '" class="btn btn-danger excluir">'. $ativar.'</a></td>
                </tr>';
        }
    }else{
        echo '<tr>
                <th colspan="6">Nenhum Curso encontrado</th>
            </tr>';
    }
}

if(isset($_GET['OP'])) {
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar Cursos
        case 1:
                $cDAO = new CursoDAO();
                $cursos = $cDAO->listarCursos();
                
                montarListaCurso($cursos);
            break;
        //Buscar Cursos por nome
        case 2:
            if (isset($_GET['BUSCA'])) {
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
                
                $cDAO = new CursoDAO();
                $cursos = $cDAO->buscarCursoPorNome($busca);
                
                montarListaCurso($cursos);
            }else{
                header('Location: ../alterarCadastros/alterarCurso.php?OP=1');
            }

            break;

        //Alterar Curso
        case 3:
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                
                $cDAO = new CursoDAO();
                $curso = $cDAO->buscarCursosPorId($id);
                if(is_array($curso)){
                    echo json_encode($curso);
                }else{
                    $error = array("error" => "Nenhum Curso encontrado.");
                    echo json_encode($error);
                }

            }

            break;

        default:
            header('Location: ../alterarCadastros/alterarCurso.php?OP=1');
            break;

    }

}else{
    header('Location: ../alterarCadastros/alterarCurso.php?OP=1');
}

?>