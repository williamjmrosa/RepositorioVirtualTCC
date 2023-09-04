<?php
require '../persistencia/conexaobanco.class.php';

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
            //throw $th;
        }
    }

    //ADD Curso a um Campus
    public function cadastrarCursoNoCampus($idCampus,$idCurso){
        try{
            $stat = $this->conexao->prepare("insert into campus_curso(idCampus,idCurso) values(?,?)");

            $stat->bindValue(1,$idCampus);
            $stat->bindValue(2,$idCurso);

            $stat->execute();

        }catch (PDOException $ex){
            return $ex;
        }
    }


}
?>