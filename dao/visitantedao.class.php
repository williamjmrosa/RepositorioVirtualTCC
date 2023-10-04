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

}

?>