<?php
include_once '../dao/alunodao.class.php';
include_once '../Modelo/aluno.class.php';

function mostrarAlunos($alunos){
   if(is_array($alunos)){
         foreach ($alunos as $aluno) {
              echo '<option value="' . $aluno->matricula . '">' . $aluno->nome . '</option>';
         }
    }else{
        echo '<option value="0">Nenhum Aluno encontrado</option>';
    }
}

if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $alunoDAO = new AlunoDAO();
    if($busca != ""){
        $alunos = $alunoDAO->buscarAlunoPorNome($busca,false);

        mostrarAlunos($alunos);

    }else{
        $alunos = $alunoDAO->listarAlunos(false);
        mostrarAlunos($alunos);
    }

}else{
    $alunoDAO = new AlunoDAO();
    $alunos = $alunoDAO->listarAlunos(false);
    mostrarAlunos($alunos);
}

?>