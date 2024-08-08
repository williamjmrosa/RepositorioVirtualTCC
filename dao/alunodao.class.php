<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../dao/enderecodao.class.php';
include_once '../dao/campusdao.class.php';
include_once '../dao/cursodao.class.php';

class AlunoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Função para gerar uma matrícula numérica baseada em data e hora
    function gerarMatriculaNumerica(int $id) {
        // Obter o ano atual
        $ano = date('Y');
        // Obter o mes atual
        $mes = date('n');  
        // Obter o semestre atual (1 para primeiro semestre, 2 para segundo semestre)
        $semestre = ($mes <= 6) ? 1 : 2;
        // Obter o dia atual
        $dia = date('d');
    
        // Gerar a matrícula com base nos valores obtidos
        $matricula = $ano . $semestre . $mes . $dia . $id;

        return $matricula;
    }

    //Cadastrar Aluno
    public function cadastrarAluno(Aluno $aluno){
        try{

            $EndDAO = new EnderecoDAO();

            $aluno->end->idEndereco = $EndDAO->cadastrarEndereco($aluno->end);
            
            $stat = $this->conexao->prepare("insert into aluno(matricula,nome,rg,cpf,telefone,email,senha,idEndereco,campus,curso) values(?,?,?,?,?,?,?,?,?,?)");

            $aluno->matricula = $this->gerarMatriculaNumerica($aluno->end->idEndereco);

            $stat->bindValue(1,$aluno->matricula);
            $stat->bindValue(2,$aluno->nome);
            $stat->bindValue(3,$aluno->rg);
            $stat->bindValue(4,$aluno->cpf);
            $stat->bindValue(5,$aluno->telefone);
            $stat->bindValue(6,$aluno->email);
            $stat->bindValue(7,$aluno->senha);
            $stat->bindValue(8,$aluno->end->idEndereco);
            $stat->bindValue(9,$aluno->campus);
            $stat->bindValue(10,$aluno->curso);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Listar Alunos
    public function listarAlunos($todos = true){
        try{
            if($todos){
                $stat = $this->conexao->prepare("select * from aluno");
            }else{
                $stat = $this->conexao->prepare("select * from aluno where ativo is null");
            }

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');

            return $array;

        }catch(PDOException $ex){
            return false;
        }
    }

    // Buscar Aluno por nome
    public function buscarAlunoPorNome($nome, $todos = true){
        try{
            if($todos){
                $stat = $this->conexao->prepare("select * from aluno where nome like ?");
            }else{
                $stat = $this->conexao->prepare("select * from aluno where ativo is null and nome like ?");
            }

            $stat->bindValue(1, $nome."%");
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');

            return $array;

        }catch(PDOException $ex){
            return false;
        }
    }

    // Buscar Aluno por tipo
    public function buscarAlunoPorTipo($busca,$tipo){
        try{  

            $stat = $this->conexao->prepare("select * from aluno where $tipo like ?");
            $stat->bindValue(1, $busca."%");
            $stat->execute();

            $alunos = $stat->fetchAll(PDO::FETCH_CLASS, 'Aluno');
            echo $stat->debugDumpParams();
            return $alunos;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Aluno por matricula
    public function buscarAlunoPorMatricula($id, $array = true){
        try{
            $stat = $this->conexao->prepare("select * from aluno where matricula = ?");
            $stat->bindValue(1, $id);
            $stat->execute();
            
            $EndDAO = new EnderecoDAO();

            if($array == true){
                $aluno = $stat->fetch(PDO::FETCH_ASSOC);
                $aluno['end'] = $EndDAO->encontrarEnderecoPorId($aluno['idEndereco']);
            }else{
                $aluno = $stat->fetchObject('Aluno'); 
                $aluno->end = $EndDAO->encontrarEnderecoPorId($aluno->idEndereco, false);
                $campusDAO = new CampusDAO();
                $aluno->campus = $campusDAO->buscarCampusAluno($aluno->matricula);
                $cursoDAO = new CursoDAO();
                $aluno->curso = $cursoDAO->buscarCursoAluno($aluno->matricula);
            }

            
            

            return $aluno;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }

    }

    // Alterar Aluno por ADM/Bibliotecario
    public function alterarAlunoADM(Aluno $aluno){
        try{

            $sql = 'update aluno set';
            $params = array();
            if($aluno->nome != null){
                $sql .= ($sql == "update aluno set") ? " nome = :nome" : ", nome = :nome";
                $params[':nome'] = $aluno->nome;
            }
            if($aluno->rg != null){
                $sql.= ($sql == "update aluno set") ? " rg = :rg" : ", rg = :rg";
                $params[':rg'] = $aluno->rg;
            }
            if($aluno->cpf != null){
                $sql.= ($sql == "update aluno set") ? " cpf = :cpf" : ", cpf = :cpf";
                $params[':cpf'] = $aluno->cpf;
            }
            if($aluno->telefone != null){
                $sql.= ($sql == "update aluno set") ? " telefone = :telefone" : ", telefone = :telefone";
                $params[':telefone'] = $aluno->telefone;
            }
            if($aluno->email != null){
                $sql.= ($sql == "update aluno set") ? " email = :email" : ", email = :email";
                $params[':email'] = $aluno->email;
            }
            if($aluno->senha != null){
                $sql.= ($sql == "update aluno set") ? " senha = :senha" : ", senha = :senha";
                $params[':senha'] = $aluno->senha;
            }
            if($aluno->campus != null){
                $sql.= ($sql == "update aluno set") ? " campus = :campus" : ", campus = :campus";
                $params[':campus'] = $aluno->campus;
            }
            if($aluno->curso != null){
                $sql.= ($sql == "update aluno set") ? " curso = :curso" : ", curso = :curso";
                $params[':curso'] = $aluno->curso;
            }
            $sql.=" where matricula = :matricula";
            $stat = $this->conexao->prepare($sql);
            
            $params[':matricula'] = $aluno->matricula;
            
            foreach($params as $key => $value){
                $stat->bindValue($key, $value);
            }
            
            $stat->execute();
            $EndDAO = new EnderecoDAO();
            if($EndDAO->alterarEndereco($aluno->end)){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo "Aluno: ".$ex->getMessage();
        }

    }

    // Ativar/Desativar Aluno
    public function alterarStatusAluno($id){
        try{
            $stat = $this->conexao->prepare("update aluno set ativo = IF(ativo = 1,NULL,1) where matricula = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }

    }

    // Verificar se email ja existe
    public static function verificarEmail($email){
        try{
            $stat = ConexaoBanco::getInstancia()->prepare("select * from aluno where email = ?");
            $stat->bindValue(1, $email);
            $stat->execute();
            $result = $stat->fetch();

            if($result){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return null;
        }

    }

    //Fazer login
    public function fazerLogin($usuario, $senha){
        try{
            $stat = $this->conexao->prepare("select * from aluno where (email = ? or matricula = ?) and senha = ?");
            $stat->bindValue(1, $usuario);
            $stat->bindValue(2, $usuario);
            $stat->bindValue(3, $senha);
            $stat->execute();

            $aluno = $stat->fetchObject("Aluno");

            return $aluno;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return null;
        }

    }

}
?>