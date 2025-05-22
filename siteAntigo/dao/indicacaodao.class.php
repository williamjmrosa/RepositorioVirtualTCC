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
    public function cadastrarIndicacao($idTCC, $idUsuario, $instituicao, $curso, $idAlunos = null){
        try{
            if($this->foiIndicado($idTCC, $idUsuario, $instituicao, $curso)){
                if($idAlunos != null && is_array($idAlunos) && count($idAlunos) > 0){
                    
                    $idIndicacao = $this->idIndicacao($idTCC, $idUsuario, $instituicao, $curso);

                    $retorno = $this->cadastrarIndicacaoAluno($idIndicacao, $idAlunos);
                    
                    if($retorno == "true"){
                        echo json_encode("Indicação já estavá cadastrada!!\nAluno(s) indicado(s) com sucesso!!");
                        return true;
                    }else if($retorno == "parcial"){
                        echo json_encode("Indicação já estavá cadastrada!!\nAluno(s) indicado(s) com sucesso!!, mas alguns alunos já foram indicados!!");
                        return true;
                    }else{
                        $erros = array();
                        $erros[] = "Erro Aluno(s) já Indicado(s)!!";
                        $resposta['erros'] = $erros;
                        echo json_encode($resposta);
                        return false;
                    }

                }else{
                    $erros = array();
                    $erros[] = "Erro Indicação já foi cadastrada!!";
                    $resposta['erros'] = $erros;
                    echo json_encode($resposta);
                    return false;
                }

            }else{
                $stat = $this->conexao->prepare("insert into indicacao(idIndicacao,idTCC,matricula,idInstituicao,idCurso) values(null,?,?,?,?)");

                $stat->bindValue(1,$idTCC);
                $stat->bindValue(2,$idUsuario);
                $stat->bindValue(3,$instituicao);
                $stat->bindValue(4,$curso);

                $stat->execute();

                $idIndicacao = $this->conexao->lastInsertId();

                if($idAlunos != null && is_array($idAlunos) && count($idAlunos) > 0){
                    echo json_encode("Indicação cadastrada com sucesso!\n Aluno(s) indicado(s) com sucesso!!");
                    $this->cadastrarIndicacaoAluno($idIndicacao, $idAlunos);
                }else{
                    echo json_encode("Indicação cadastrada com sucesso!");
                }

                return true;
            }
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            $erros = array();
            $erros[] = "Erro ao cadastrar Indicação!!";
            $resposta['erros'] = $erros;
            echo json_encode($resposta);
            return false;
        }
    }

    // Cadastrar Indicação para um Aluno
    public function cadastrarIndicacaoAluno($idIndicacao, $idAlunos){
        try{
            $repetido = 0;
            foreach ($idAlunos as $aluno) {
                
                if(!$this->foiIndicadoAluno($idIndicacao, $aluno)){
                    $stat = $this->conexao->prepare("insert into indica_para_aluno(idIndicaAluno,idIndicacao,matricula) values(null,?,?)");
                    
                    $stat->bindValue(1,$idIndicacao);
                    $stat->bindValue(2,$aluno);
                    $stat->execute();
                }else{
                    $repetido = $repetido + 1;
                }
            }
            
            if($repetido == count($idAlunos)){
                return "false";
            }else if($repetido > 0 && $repetido < count($idAlunos)){
                return "parcial";
            }else{
                return "true";
            }
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }

    //Excluir Indicação
    public function excluirIndicacao($idTCC,$idCurso,$idInstituicao,$matricula){
        try{

            $idIndicacao = $this->idIndicacao($idTCC, $matricula, $idInstituicao, $idCurso);

            $stat = $this->conexao->prepare("delete from indica_para_aluno where idIndicacao = ?");
            $stat->bindValue(1,$idIndicacao);

            $stat->execute();

            $stat = $this->conexao->prepare("delete from indicacao where idIndicacao = ?");
            $stat->bindValue(1,$idIndicacao);

            $stat->execute();
            
            return true;
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            //$erro['erro'] = $ex->getMessage();
            //echo json_encode($erro);
            return false;
        }
    }

    // Excluir Indicação para um Aluno
    public function excluirIndicacaoAluno($idIndicaAluno){
        try{
            $stat = $this->conexao->prepare("delete from indica_para_aluno where idIndicaAluno = ?");
            $stat->bindValue(1,$idIndicaAluno);
            $stat->execute();
            return true;
        }catch(PDOException $ex){
            //echo $ex->getMessage();
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

    // Listar Alunos que recebeu Indicação de um Professor
    public function listarAlunosIndicados($matricula){
    
        try{
            $sql = "SELECT * FROM aluno WHERE matricula in (select ia.matricula from indicacao as i INNER JOIN indica_para_aluno as ia ON i.idIndicacao = ia.idIndicacao WHERE i.matricula = ?)";

            $stat = $this->conexao->prepare($sql);

            $stat->bindValue(1,$matricula);
            
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }

    }

    public function listarTCCsIndicadosParaAluno($matricula, $matriculaProfessor = null){
        try{
            //$sql = "SELECT t.idTCC, t.titulo, i.idIndicacao FROM tcc as t INNER JOIN indicacao as i ON i.idTCC = t.idTCC WHERE i.idIndicacao in (SELECT idIndicacao FROM indica_para_aluno WHERE matricula = ?)";

            $sql = "SELECT t.idTCC, t.titulo, ia.idIndicaAluno FROM tcc as t INNER JOIN indicacao as i ON i.idTCC = t.idTCC INNER JOIN indica_para_aluno as ia ON i.idIndicacao = ia.idIndicacao WHERE ia.matricula = ?";

            if($matriculaProfessor != null){
                $sql = $sql . " AND i.matricula = ?";
            }

            $stat = $this->conexao->prepare($sql);

            $stat->bindValue(1,$matricula);

            if($matriculaProfessor != null){
                $stat->bindValue(2,$matriculaProfessor);
            }
            
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_ASSOC);

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

    // Listar Alunos para serem indicados ao TCC
    public function alunosParaIndicar($campus, $curso){
        try{
            $stat = $this->conexao->prepare("select * from aluno where campus = ? and curso = ? and ativo is null");
            $stat->bindValue(1,$campus);
            $stat->bindValue(2,$curso);
            $stat->execute();
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');
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

    // Foi Indicado para Aluno
    public function foiIndicadoAluno($idIndicacao, $matricula){
        try{
            $stat = $this->conexao->prepare("select count(*) from indica_para_aluno where idIndicacao = ? and matricula = ?");
            $stat->bindValue(1,$idIndicacao);
            $stat->bindValue(2,$matricula);
            $stat->execute();
            $res = $stat->fetch();
            if($res[0] > 0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }

    // Descobrir o idIndicacao
    public function idIndicacao($idTCC, $idUsuario, $instituicao, $curso){
        try{
            $stat = $this->conexao->prepare("select idIndicacao from indicacao where idTCC = ? and matricula = ? and idInstituicao = ? and idCurso = ?");
            $stat->bindValue(1,$idTCC);
            $stat->bindValue(2,$idUsuario);
            $stat->bindValue(3,$instituicao);
            $stat->bindValue(4,$curso);
            $stat->execute();
            $idIndicacao = $stat->fetch(PDO::FETCH_ASSOC);
            $idIndicacao = $idIndicacao['idIndicacao'];
            return $idIndicacao;

        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }
}

?>