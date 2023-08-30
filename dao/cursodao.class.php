<?php
require '../persistencia/conexaobanco.class.php';

class CursoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    //Cadastra um Curso no banco
    public function cadastrarCurso(Curso $c){
        try {
            $stat = $this->conexao->prepare("insert into curso(idcurso,nome,ensino) values(null,?,?)");

            $stat->bindValue(1, $c->nome);
            $stat->bindValue(2, $c->ensino);
         

            $stat->execute();

        } catch (PDOException $ex) {
            return "Erro ao Cadastrar! \n".$ex->errorInfo[2];
        }//fecha catch
    }//fecha cadastrarCurso

    //Listar Cursos
    public function listarCursos(){
        try{
            $stat = $this->conexao->prepare("Select * From curso");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');

            return $array;

        } catch (PDOException $ex){
            return "Erro ao listar Cursos! \n".$ex->errorInfo[2];
        }
    }

}

?>