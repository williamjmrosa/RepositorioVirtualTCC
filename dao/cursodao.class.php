<?php
require_once '../persistencia/conexaobanco.class.php';

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
            return "Erro ao Cadastrar! \n".$ex->getMessage();
        }//fecha catch
    }//fecha cadastrarCurso

    //Listar Cursos
    public function listarCursos(){
        try{
            $stat = $this->conexao->prepare("Select * From curso order by ensino");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');

            return $array;

        } catch (PDOException $ex){
            return "Erro ao listar Cursos! \n".$ex->getMessage();
        }
    }

    //Buscar por curso de um campus
    public function buscarCursoCampus($idCampus){
        try{
            $stat = $this->conexao->prepare("Select c.idCurso, c.nome, c.ensino From curso c inner join campus_curso cc on c.idcurso = cc.idcurso where cc.idcampus =?");
            $stat->bindValue(1, $idCampus);
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');

            return $array;
        }catch(PDOException $ex){
            return "Erro ao buscar Curso da Campus! \n".$ex->getMessage();
        }
    }

    // Buscar curso de um aluno
    public function buscarCursoAluno($idAluno){
        try{
            $stat = $this->conexao->prepare("Select c.idcurso, c.nome, c.ensino From curso c inner join aluno a on c.idcurso = a.curso where a.matricula =?");
            $stat->bindValue(1, $idAluno);
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Curso do Aluno! \n".$ex->getMessage();
        }
    }

}

?>