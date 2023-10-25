<?php
require_once '../persistencia/conexaobanco.class.php';

class CampusDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    //Cadastrar um Campos no banco
    public function cadastrarCampus(Campus $c){
        try {
            $stat = $this->conexao->prepare("insert into campus(idcampus,nome) values(null,?)");

            $stat->bindValue(1, $c->nome);

            $stat->execute();

            $id =  $this->conexao->lastInsertId();

            return $id;

        } catch (PDOException $ex) {
            return $ex->getMessage();
        }
    }

    //ADD Curso a um Campus
    public function cadastrarCursoNoCampus($idCampus,$idCurso){
        try {
            $stat = $this->conexao->prepare("insert into campus_curso(idCampus,idCurso) values(?,?)");

            $stat->bindValue(1,$idCampus);
            $stat->bindValue(2,$idCurso);

            $stat->execute();

        } catch (PDOException $ex) {
            return $ex->getMessage();
        }
    }

    //Listar Campus
    public function listarCampus(){
        try{
            $stat = $this->conexao->prepare("Select * From campus order by nome");
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Campus');
            
            return $array;

        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }

    // Buscar Campus de um Aluno
    public function buscarCampusAluno($matricula){
        try{
            $stat = $this->conexao->prepare("Select c.idcampus,c.nome From campus as c inner join aluno as a on c.idcampus = a.campus where a.matricula = ?");
            
            $stat->bindValue(1,$matricula);
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Campus');
            
            return $array;
        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }

    // Buscar Campus por nome
    public function buscarCampusPorNome($nome){
        try{
            $stat = $this->conexao->prepare("Select * From campus where nome like ?");
            
            $stat->bindValue(1, $nome."%");
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Campus');
            
            return $array;
        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }

}
?>