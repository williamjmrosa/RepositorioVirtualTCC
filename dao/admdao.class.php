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

    // Alterar Bibliotecario
    public function alterarAdministrador(Adm $adm, $email){
        
        try {
            
            $sql = "update adm set";
            $parametros = array();
            if($adm->nome != null && $adm->nome != ""){
                $sql = $sql == "update adm set" ? $sql . " nome = :nome" : $sql . ", nome = :nome";
                $parametros[':nome'] = $adm->nome;
            }

            if($adm->email != null && $adm->email != "" && $adm->email != $email){
                $sql = $sql == "update adm set" ? $sql . " email = :email" : $sql . ", email = :email";
                $parametros[':email'] = $adm->email;
            }

            if($adm->senha != null && $adm->senha != ""){
                $sql = $sql == "update adm set" ? $sql . " senha = :senha" : $sql . ", senha = :senha";
                $parametros[':senha'] = $adm->senha;
            }

            $sql = $sql . " where email = :id";

            $parametros[':id'] = $adm->email;

            $stat = $this->conexao->prepare($sql);

            $stat->execute($parametros);

            return true;

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    // Listar Administradores
    public function listarAdministradores(){
        try{
            $stat = $this->conexao->prepare("select nome,email from adm");
            $stat->execute();

            $administradores = $stat->fetchAll(PDO::FETCH_CLASS, 'Adm');

            return $administradores;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Administrador por e-mail
    public function encontrarAdmPorEmail($email){
        try{
            $stat = $this->conexao->prepare("select * from adm where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            $result = $stat->fetch(PDO::FETCH_ASSOC);

            return $result;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Administrador por tipo
    public function encontrarAdmPorTipo($busca, $tipo){
        try{
            $stat = $this->conexao->prepare("select nome, email from adm where $tipo like ?");
            $stat->bindValue(1,$busca.'%');
            $stat->execute();

            $administradores = $stat->fetchAll(PDO::FETCH_CLASS, 'Adm');

            return $administradores;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Excluir Administrador
    public function excluirAdm($email){
        try{
            $stat = $this->conexao->prepare("delete from adm where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Fazer Login
    function fazerLogin($email, $senha){
        try{
            $stat = $this->conexao->prepare("select * from adm where email = ? and senha = ?");

            $stat->bindValue(1,$email);
            $stat->bindValue(2,$senha);
            $stat->execute();

            $adm = $stat->fetchObject('Adm');

            return $adm;
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}
?>