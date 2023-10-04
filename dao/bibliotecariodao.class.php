<?php
require_once '../persistencia/conexaobanco.class.php';

class BibliotecarioDAO{
    
    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Bibliotecario
    public function cadastrarBibliotecario(Bibliotecario $bibliotecario){
        
        try {
            
            $stat = $this->conexao->prepare("insert into bibliotecario(nome,email,senha) values(?,?,?)");
        
            $stat->bindValue(1,$bibliotecario->nome);
            $stat->bindValue(2,$bibliotecario->email);
            $stat->bindValue(3,$bibliotecario->senha);

            $stat->execute();

            return true;

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }

    }

    //Verificar se o e-mail já existe no banco
    public static function verificarEmail($email):bool{
        try{
            $stat = ConexaoBanco::getInstancia()->prepare("select * from bibliotecario where email = ?");
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