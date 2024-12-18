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
            //$stat->debugDumpParams();
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
    public function excluirVisitante($email){
        try{
            if($this->excluirTodosFavoritosVisitante($email)){    
                
                $stat = $this->conexao->prepare("delete from visitante where email = ?");
                $stat->bindValue(1,$email);
                $stat->execute();

                return true;

            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Excluir Todos os Favoritos de um Visitante
    public function excluirTodosFavoritosVisitante($email){
        try{
            $stat = $this->conexao->prepare("delete from favorito_visitante where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
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
    public function encontrarVisitantePorEmail($email,$array = true){
        try{
            $stat = $this->conexao->prepare("select * from visitante where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            if($array){
                $visitantes = $stat->fetch(PDO::FETCH_ASSOC);
            }else{
                $visitantes = $stat->fetchObject('Visitante');
            }

            return $visitantes;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }
    
    // Fazer login
    public function fazerLogin($email, $senha){
        try{
            $stat = $this->conexao->prepare("select * from visitante where email = ? and senha = ?");
            $stat->bindValue(1, $email);
            $stat->bindValue(2, $senha);
            $stat->execute();

            $visitante = $stat->fetchObject('Visitante');

            return $visitante;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}

?>