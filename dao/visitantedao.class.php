<?php
require_once '../persistencia/conexaobanco.class.php';

class VisitanteDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Visitante
    public function cadastrarVisitante(Visitante $visitante){
        try{
            
            $stat = $this->conexao->prepare("insert into visitante(nome,email,senha) values(?,?,?)");

            $stat->bindValue(1,$visitante->nome);
            $stat->bindValue(2,$visitante->email);
            $stat->bindValue(3,$visitante->senha);

            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Verificar se email já existe
    public static function verificarEmail($email):bool{
        try{
            $stat = ConexaoBanco::getInstancia()->prepare("select * from visitante where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            $result = $stat->fetch();
            
            if($result){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            return false;//echo $ex->getMessage();
        }
        
    }

    // Alterar Visitante
    public function alterarVisitante(Visitante $visitante,$email){
        try{
            
            $sql = "update visitante set";
            $parametros = array();
            if($visitante->nome != null && $visitante->nome != ""){
                $sql = $sql == "update visitante set" ? $sql . " nome = :nome" : $sql . ", nome = :nome";
                $parametros[':nome'] = $visitante->nome;
            }
            
            if($visitante->email != null && $visitante->email != "" && $visitante->email != $email){
                $sql = $sql == "update visitante set" ? $sql . " email = :email" : $sql . ", email = :email";
                $parametros[':email'] = $visitante->email;
            }

            if($visitante->senha != null && $visitante->senha != ""){
                $sql = $sql == "update visitante set" ? $sql . " senha = :senha" : $sql . ", senha = :senha";
                $parametros[':senha'] = $visitante->senha;
            }

            $sql = $sql . " where email = :id";

            $parametros[':id'] = $email;

            $stat = $this->conexao->prepare($sql);
            $stat->execute($parametros);

            return true;

        }catch(PDOException $ex){
            $stat->debugDumpParams();
            echo $ex->getMessage();
        }
    }

    // Listar Visitantes
    public function listarVisitantes(){
        try{
            $stat = $this->conexao->prepare("select nome,email from visitante");
            $stat->execute();

            $visitantes = $stat->fetchAll(PDO::FETCH_CLASS, 'Visitante');

            return $visitantes;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }

    }

    // Excluir Visitante
    public function excluirVisitante($matricula){
        try{
            $stat = $this->conexao->prepare("delete from visitante where matricula = ?");
            $stat->bindValue(1,$matricula);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Visitante por tipo
    public function encontrarVisitantePorTipo($busca, $tipo){
        try{
            $stat = $this->conexao->prepare("select nome, email from visitante where $tipo like ?");
            $stat->bindValue(1,$busca.'%');
            $stat->execute();

            $visitantes = $stat->fetchAll(PDO::FETCH_CLASS, 'Visitante');

            return $visitantes;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Visitante por email
    public function encontrarVisitantePorEmail($email){
        try{
            $stat = $this->conexao->prepare("select * from visitante where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            $visitantes = $stat->fetch(PDO::FETCH_ASSOC);

            return $visitantes;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }
    

}

?>