<?php
require_once '../persistencia/conexaobanco.class.php';

class AdmDAO{
    
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Administrador
    public function cadastrarAdministrador(Adm $adm){
        
        try {
            
            $stat = $this->conexao->prepare("insert into adm(email,nome,senha) values(?,?,?)");

            $stat->bindValue(1,$adm->email);
            $stat->bindValue(2,$adm->nome);
            $stat->bindValue(3,$adm->senha);

            $stat->execute();

            return true;

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }

    }

    //Verificar se o e-mail já existe no banco
    public static function verificarEmail($email):bool{
        try{
            $stat = ConexaoBanco::getInstancia()->prepare("select * from adm where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            $result = $stat->fetch();

            if($result){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $ex){
            return false;
        }
    }

}
?>