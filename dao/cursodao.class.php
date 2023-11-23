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
            $stat = $this->conexao->prepare("Select * From curso");

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
            
            $array = $stat->fetchObject('Curso');
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Curso do Aluno! \n".$ex->getMessage();
        }
    }

    // Buscar curso por nome
    public function buscarCursoPorNome($nome){
        try{
            $stat = $this->conexao->prepare("Select * From curso where nome like ?");
            $stat->bindValue(1, $nome."%");
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Curso por Nome! \n".$ex->getMessage();
        }
    }

    // Buscar curso de um campus
    public function buscarCursoDeCampus($idCampus){
        try{
            $stat = $this->conexao->prepare("Select c.idCurso, c.nome, c.ensino From curso as c inner join campus_curso cc on c.idCurso = cc.idCurso where cc.idcampus = ?");
            $stat->bindValue(1, $idCampus);
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Curso por ID! \n".$ex->getMessage();
        }
    }

    // Buscar curso por nome nos curso de um campus
    public function buscarCursoPorNomeCampus($nome, $idCampus){
        try{
            $stat = $this->conexao->prepare("Select c.idCurso, c.nome, c.ensino From curso c inner join campus_curso cc on c.idCurso = cc.idCurso where cc.idCampus = ? and c.nome like ?");
            $stat->bindValue(1, $idCampus);
            $stat->bindValue(2, $nome."%");
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Curso por Nome! \n".$ex->getMessage();
        }
    }

    // Buscar Cursos por ID
    public function buscarCursosPorId($id){
        try{
            $stat = $this->conexao->prepare("Select * From curso where idCurso = ?");
            $stat->bindValue(1, $id);
            $stat->execute();
            
            $array = $stat->fetch(PDO::FETCH_ASSOC);
        
            return $array;
        }catch(PDOException $ex){
            echo "Erro ao buscar Cursos por ID! \n".$ex->getMessage();
        }
    }

    // Alterar Curso
    public function alterarCurso(Curso $c){
        try{
            $stat = $this->conexao->prepare("update curso set nome = ?, ensino = ? where idCurso = ?");
            $stat->bindValue(1, $c->nome);
            $stat->bindValue(2, $c->ensino);
            $stat->bindValue(3, $c->idCurso);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo "Erro ao alterar Curso! \n".$ex->getMessage();
        }
    }

    // Ativar e Desativar Cursos
    public function alterarStatusCurso($id){
        try{
            $stat = $this->conexao->prepare("update curso set ativo = IF(ativo is null,1,null) where idCurso = ?");
            $stat->bindValue(1, $id);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo "Erro ao alterar Status do Curso! \n".$ex->getMessage();
        }
    }
}

?>