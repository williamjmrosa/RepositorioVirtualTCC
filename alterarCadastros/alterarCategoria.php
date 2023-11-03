<?php
include_once '../Modelo/categoria.class.php';
include_once '../dao/categoriadao.class.php';
function montarListaCategoria($categorias){
    if (is_array($categorias) && count($categorias) > 0) {
        foreach ($categorias as $c) {
            $eSub = $c->eSub == 0 ? 'Não' : 'Sim';
            echo '<tr>
                    <th scope="row">' . $c->idCategoria . '</th>
                        <td>' . $c->nome . '</td>
                        <td>' . $eSub . '</td>
                        <td>' . $c->categoriaPrincipal . '</td>
                        <td><a href="' . $c->idCategoria . '" class="btn btn-primary alterarCategoria" onclick="preencherForm(this,event)">Alterar</a></td>
                        <td><a href="../controle/categoria-controle.php?OP=3&id=' . $c->idCategoria . '" class="btn btn-danger excluir">Excluir</a></td>
                </tr>';
        }
    }
}

if(isset($_GET['OP'])) {
    $OP = filter_var($_GET['OP'], FILTER_SANITIZE_NUMBER_INT);

    switch ($OP) {
        //Listar Categorias
        case 1:
                $cDAO = new CategoriaDAO();
                $categorias = $cDAO->listarCategoria();
                
                montarListaCategoria($categorias);
            break;
        //Buscar Categorias por nome
        case 2:
            if (isset($_GET['BUSCA'])) {
                $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);
                
                $cDAO = new CategoriaDAO();
                $categorias = $cDAO->buscarCategoriasPorNome($busca);
                
                montarListaCategoria($categorias);
            }else{
                header('Location: ../alterarCadastros/alterarCategoria.php?OP=1');
            }
            break;
        //Alterar Categoria
        case 3:
            if(isset($_GET['id'])){
                $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                
                $cDAO = new CategoriaDAO();
                $categoria = array();
                $categoria[] = $cDAO->buscarCategoriasPorId($id);
                if(!empty($categoria)){
                    echo json_encode($categoria);
                }else{
                    trigger_error("Nenhuma categoria encontrada.", E_USER_ERROR);     
                }

                //montarListaCategoria($categoria);

            }
            break;
        default:
            header('Location: ../alterarCadastros/alterarCategoria.php?OP=1');
            break;

    }
}else{
    header('Location: ../alterarCadastros/alterarCategoria.php?OP=1');
}
?>
