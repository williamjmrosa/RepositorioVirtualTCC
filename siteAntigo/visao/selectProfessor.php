<?php
include_once '../dao/professordao.class.php';
include_once '../Modelo/professor.class.php';


function mostrarProfessores($professores){
    if(count($professores) > 0){
          foreach ($professores as $professor) {
               echo '<option value="' . $professor->matricula . '" onClick="listaOrientador(this)">' . $professor->nome . '</option>';
          }
     }else{
         echo '<option value="0">Nenhum Professor encontrado</option>';
     }
}

if(isset($_GET['BUSCA'])){
    $busca = filter_var($_GET['BUSCA'], FILTER_SANITIZE_SPECIAL_CHARS);

    $professorDAO = new ProfessorDAO();
    
    if($busca != ""){
        $professores = $professorDAO->buscarProfessorPorNome($busca,false);

        mostrarProfessores($professores);
    }else{
        $professores = $professorDAO->listarProfessores(false);
        mostrarProfessores($professores);
    }

}else{
    $professorDAO = new ProfessorDAO();
    $professores = $professorDAO->listarProfessores(false);
    mostrarProfessores($professores);
}
?>