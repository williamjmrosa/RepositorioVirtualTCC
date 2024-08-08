<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../dao/enderecodao.class.php';

class ProfessorDAO{
    
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

    //Cadastrar Professor
    public function cadastrarProfessor(Professor $professor){
        try{

            $EndDAO = new EnderecoDAO();

            $professor->end->idEndereco = $EndDAO->cadastrarEndereco($professor->end);

            $stat = $this->conexao->prepare("insert into professor(matricula,nome,rg,cpf,telefone,email,senha,idEndereco) values(?,?,?,?,?,?,?,?)");

            $professor->matricula = $this->gerarMatriculaNumerica($professor->end->idEndereco);

            $stat->bindValue(1,$professor->matricula);
            $stat->bindValue(2,$professor->nome);
            $stat->bindValue(3,$professor->rg);
            $stat->bindValue(4,$professor->cpf);
            $stat->bindValue(5,$professor->telefone);
            $stat->bindValue(6,$professor->email);
            $stat->bindValue(7,$professor->senha);
            $stat->bindValue(8,$professor->end->idEndereco);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    //Listar Professores
    public function listarProfessores($todos = true){
        try{
            if($todos){
                $stat = $this->conexao->prepare("Select * from professor");
            }else{
                $stat = $this->conexao->prepare("Select * from professor where ativo is null");
            }
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    //Buscar Professor por nome
    public function buscarProfessorPorNome($nome,$todos = true){
        try{
            if($todos){
                $stat = $this->conexao->prepare("Select * from professor where nome like ?");
            }else{
                $stat = $this->conexao->prepare("Select * from professor where nome like ? and ativo is null");
            }

            $stat->bindValue(1, $nome."%");
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    //Buscar Professor por matricula
    public function buscarProfessorPorMatricula($matricula, $array = true){
        try{
            $stat = $this->conexao->prepare("Select * From professor where matricula = ?");
            $stat->bindValue(1, $matricula);
            $stat->execute();
            $endDAO = new EnderecoDAO();
            if($array){
                $professor = $stat->fetch(PDO::FETCH_ASSOC);
                $professor['end'] = $endDAO->encontrarEnderecoPorId($professor['idEndereco']);
            }else{
                $professor = $stat->fetchObject('Professor');
                $professor->end = $endDAO->encontrarEnderecoPorId($professor->idEndereco, false);
            } 
            

            return $professor;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    //Buscar Professor por tipo
    public function buscarProfessorPorTipo($busca, $tipo){
        try{
            $stat = $this->conexao->prepare("Select * From professor where $tipo like ?");
            $stat->bindValue(1, $busca."%");
            $stat->execute();

            $professores = $stat->fetchAll(PDO::FETCH_CLASS, 'Professor');

            return $professores;
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    //Alterar Professor por ADM/Bibliotecario
    public function alterarProfessorADM(Professor $professor){
        try{
            $sql = 'update professor set';
            $params = array();
            if($professor->nome != null){
                $sql .= ($sql == "update professor set") ? " nome = :nome" : ", nome = :nome";
                $params[':nome'] = $professor->nome;
            }
            if($professor->rg != null){
                $sql .= ($sql == "update professor set") ? " rg = :rg" : ", rg = :rg";
                $params[':rg'] = $professor->rg;
            }
            if($professor->cpf != null){
                $sql .= ($sql == "update professor set") ? " cpf = :cpf" : ", cpf = :cpf";
                $params[':cpf'] = $professor->cpf;
            }
            if($professor->telefone != null){
                $sql .= ($sql == "update professor set") ? " telefone = :telefone" : ", telefone = :telefone";
                $params[':telefone'] = $professor->telefone;
            }
            if($professor->email != null){
                $sql .= ($sql == "update professor set") ? " email = :email" : ", email = :email";
                $params[':email'] = $professor->email;
            }
            if($professor->senha != null){
                $sql .= ($sql == "update professor set") ? " senha = :senha" : ", senha = :senha";
                $params[':senha'] = $professor->senha;
            }

            $sql .= " where matricula = :matricula";
            $params[':matricula'] = $professor->matricula;
            $stat = $this->conexao->prepare($sql);

            foreach($params as $k => $v){
                $stat->bindValue($k, $v);
            }

            $stat->execute();

            $endDAO = new EnderecoDAO();
            if($endDAO->alterarEndereco($professor->end)){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }
    
    //Ativar/Desativar Professor
    public function alterarStatusProfessor($id){
        try{
            $stat = $this->conexao->prepare("update professor set ativo = IF(ativo = 1,NULL,1) where matricula = ?");
            $stat->bindValue(1, $id);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Verificar se e-mail existe
    public static function verificarEmail($email){
        try{
            $stat = ConexaoBanco::getInstancia()->prepare("select * from professor where email = ?");
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
        }
    }

    // Fazer login
    public function fazerLogin($usuario, $senha){
        try{
            $stat = $this->conexao->prepare("select * from professor where (email = ? or matricula = ?) and senha = ?");
            $stat->bindValue(1, $usuario);
            $stat->bindValue(2, $usuario);
            $stat->bindValue(3, $senha);
            $stat->execute();

            $professor = $stat->fetchObject('Professor');

            return $professor;
        }catch(PDOException $ex){
            echo $ex->getMessage();
            return null;
        }
    }

}
?>