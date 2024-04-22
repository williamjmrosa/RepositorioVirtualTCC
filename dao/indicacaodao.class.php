<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../Modelo/aluno.class.php';
include_once '../Modelo/professor.class.php';
include_once '../Modelo/visitante.class.php';
include_once '../Modelo/bibliotecario.class.php';
include_once '../Modelo/adm.class.php';

class IndicacaoDAO{
    
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Indicação
    public function cadastrarIndicacao($idTCC, $idUsuario, $instituicao, $curso){
        try{
            if($this->foiIndicado($idTCC, $idUsuario, $instituicao, $curso)){
                return false;
            }else{
                $stat = $this->conexao->prepare("insert into indicacao(idIndicacao,idTCC,matricula,idInstituicao,idCurso) values(null,?,?,?,?)");

                $stat->bindValue(1,$idTCC);
                $stat->bindValue(2,$idUsuario);
                $stat->bindValue(3,$instituicao);
                $stat->bindValue(4,$curso);

                $stat->execute();

                return true;
            }
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Listar Todos as Indicações
    public function listarIndicacoes($idInstituicao = null, $idCurso = null,$matricula = null){
        try{

            $sql = "select * from tcc where idTCC in (select idTCC from indicacao where 1=1";

            $busca = array();

            if($idInstituicao != null){
                $sql = $sql . " and idInstituicao = :instituicao";
                $busca[":instituicao"] = $idInstituicao;
            }

            if($idCurso != null){
                $sql = $sql . " and idCurso = :curso";
                $busca[":curso"] = $idCurso;
            }

            if($matricula != null){
                $sql = $sql . " and matricula = :matricula";
                $busca[":matricula"] = $matricula;
            }

            $sql = $sql . ")";

            $stat = $this->conexao->prepare($sql);

            $stat->execute($busca);

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'TCC');

            return $array;
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Lista professores que indicaram o TCC
    public function listarProfessores($curso = null, $instituicao = null){
        try{
            $sql = "select * from professor where matricula in (select matricula from indicacao where 1=1";
            
            $busca = array();

            if($instituicao != null){
                $sql = $sql . " and idInstituicao = :instituicao";
                $busca[":instituicao"] = $instituicao;
            }
            if($curso != null){
                $sql = $sql . " and idCurso = :curso";
                $busca[":curso"] = $curso;
            }

            $sql = $sql . ")";
            $sql = $sql . " order by nome";

            $stat = $this->conexao->prepare($sql);
            
            $stat->execute($busca);

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

            return $array;
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Lista de Campus que tiveram indicações
    public function listarCampus($idInstituicao = null, $idCurso = null, $matricula = null){
        try{
            $sql = "select * from campus where idCampus in (select idInstituicao from indicacao where 1=1";

            $busca = array();

            if($idInstituicao != null){
                $sql = $sql . " and idInstituicao = :instituicao";
                $busca[":instituicao"] = $idInstituicao;
            }

            if($idCurso != null){
                $sql = $sql . " and idCurso = :curso";
                $busca[":curso"] = $idCurso;
            }

            if($matricula != null){
                $sql = $sql . " and matricula = :matricula";
                $busca[":matricula"] = $matricula;
            }

            $sql = $sql . ")";

            $stat = $this->conexao->prepare($sql);

            $stat->execute($busca);

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Campus');

            return $array;
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Lista de Cursos que tiveram indicações
    public function listarCursos($idInstituicao = null, $idCampus = null, $idProfessor = null){
        try{

            $sql = "select * from curso where idCurso in (select idCurso from indicacao where 1=1";

            $busca = array();

            if($idInstituicao != null){
                $sql = $sql . " and idInstituicao = :instituicao";
                $busca[":instituicao"] = $idInstituicao;
            }

            if($idCampus != null){
                $sql = $sql . " and idCampus = :campus";
                $busca[":campus"] = $idCampus;
            }

            if($idProfessor != null){
                $sql = $sql . " and matricula = :professor";
                $busca[":professor"] = $idProfessor;
            }

            $sql = $sql . ")";

            $stat = $this->conexao->prepare($sql);

            $stat->execute($busca);

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Curso');

            return $array;
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Listar Professores do Campus e Cursos do Aluno que indicaram o TCC
    public function listarProfessoresAluno($curso = null, $instituicao = null){
        try{
            $sql = "select * from professor where matricula in (select matricula from indicacao where 1=1";

            $busca = array();

            if($instituicao != null){
                $sql = $sql . " and idInstituicao = :instituicao";
                $busca[":instituicao"] = $instituicao;
            }

            if($curso != null){
                $sql = $sql . " and idCurso = :curso ";
                $busca[":curso"] = $curso;
            }

            $sql = $sql . ")";
            $stat = $this->conexao->prepare($sql);
            $stat->execute($busca);

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Foi Indicado
    public function foiIndicado($idTCC, $idUsuario, $instituicao, $curso){
        try{
            $stat = $this->conexao->prepare("select count(*) from indicacao where idTCC = ? and matricula = ? and idInstituicao = ? and idCurso = ?");

            $stat->bindValue(1,$idTCC);
            $stat->bindValue(2,$idUsuario);
            $stat->bindValue(3,$instituicao);
            $stat->bindValue(4,$curso);

            $stat->execute();

            $res = $stat->fetch();

            if($res[0] > 0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
        
    }

}

?>