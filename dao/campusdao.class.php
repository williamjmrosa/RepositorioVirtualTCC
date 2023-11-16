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
            $stat = $this->conexao->prepare("Select * From campus order by idCampus");
            
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
            
            $array = $stat->fetchObject('Campus');
            
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

    // Buscar Campus por idCampus
    public function buscarCampusPorId($id){
        try{
            $stat = $this->conexao->prepare("Select * From campus where idcampus = ?");
            
            $stat->bindValue(1, $id);
            
            $stat->execute();
            
            $campus = $stat->fetch(PDO::FETCH_ASSOC);
            if(is_array($campus)){
                $campus['cursos'] = $this->buscarCursoDeUmCampus($id);
            }
            
            return $campus;
        } catch (PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Curso por idCampus
    public function buscarCursoDeUmCampus($idCampus){
        try{
            $stat = $this->conexao->prepare("Select c.idCurso, c.nome, c.ensino, c.ativo From Curso as c inner join campus_curso as cc on c.idCurso = cc.idCurso where cc.idcampus = ?");
            
            $stat->bindValue(1, $idCampus);
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_ASSOC);
            
            return $array;
        } catch (PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Alterar Campus
    public function alterarCampus(Campus $c){
        try{
           $stat = $this->conexao->prepare("Update campus set nome = ? where idcampus = ?");
           $stat->bindValue(1, $c->nome);
           $stat->bindValue(2, $c->idCampus);
           $stat->execute();

           return true;

        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }

    // Desativar/Ativar Campus
    public function alterarStatusCampus($id){
        try{
           $stat = $this->conexao->prepare("Update campus set ativo = IF(ativo is null,1,null) where idcampus = ?");
           $stat->bindValue(1, $id);
           $stat->execute();

           return true;

        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }

    // Remover Curso do Campus
    public function removerCursoDoCampus($idCurso,$idCampus){
        try{
           $stat = $this->conexao->prepare("delete from campus_curso where idCurso = ? and idCampus = ?");
           $stat->bindValue(1, $idCurso);
           $stat->bindValue(2, $idCampus);
           $stat->execute();

           return true;

        } catch (PDOException $ex){
            return $ex->getMessage();
        }

    }

}
?>