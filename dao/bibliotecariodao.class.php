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

            $stat->bindValue(1, $bibliotecario->nome);
            $stat->bindValue(2, $bibliotecario->email);
            $stat->bindValue(3, $bibliotecario->senha);

            $stat->execute();

            return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    //Verificar se o e-mail já existe no banco
    public static function verificarEmail($email): bool{
        try {
            $stat = ConexaoBanco::getInstancia()->prepare("select * from bibliotecario where email = ?");
            $stat->bindValue(1, $email);
            $stat->execute();

            $result = $stat->fetch();

            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            return false; //echo $ex->getMessage();
        }
    }

    // Alterar Bibliotecario
    public function alterarBibliotecario(Bibliotecario $bibliotecario, $email){
        try {

            $sql = "update bibliotecario set";
            $parametros = array();
            if ($bibliotecario->nome != null && $bibliotecario->nome != "") {
                $sql = $sql == "update bibliotecario set" ? $sql . " nome = :nome" : $sql . ", nome = :nome";
                $parametros[':nome'] = $bibliotecario->nome;
            }

            if ($bibliotecario->email != null && $bibliotecario->email != "" && $bibliotecario->email != $email) {
                $sql = $sql == "update bibliotecario set" ? $sql . " email = :email" : $sql . ", email = :email";
                $parametros[':email'] = $bibliotecario->email;
            }

            if ($bibliotecario->senha != null && $bibliotecario->senha != "") {
                $sql = $sql == "update bibliotecario set" ? $sql . " senha = :senha" : $sql . ", senha = :senha";
                $parametros[':senha'] = $bibliotecario->senha;
            }

            $sql = $sql . " where email = :id";

            $parametros[':id'] = $bibliotecario->email;

            $stat = $this->conexao->prepare($sql);

            $stat->execute($parametros);

            return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    //Buscar Bibliotecarios por e-mail
    public function encontrarBibliotecarioPorEmail($email){
        try {
            $stat = $this->conexao->prepare("select * from bibliotecario where email = ?");
            $stat->bindValue(1, $email);
            $stat->execute();

            $result = $stat->fetch(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    // Listar Bibliotecarios
    public function listarBibliotecarios(){
        try{
            $stat = $this->conexao->prepare("select nome,email from bibliotecario");
            $stat->execute();

            $bibliotecarios = $stat->fetchAll(PDO::FETCH_CLASS, 'Bibliotecario');

            return $bibliotecarios;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Excluir Bibliotecario
    public function excluirBibliotecario($email){
        try{
            $stat = $this->conexao->prepare("delete from bibliotecario where email = ?");
            $stat->bindValue(1,$email);
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Bibliotecario por tipo
    public function encontrarBibliotecarioPorTipo($busca, $email){
        try{
            $stat = $this->conexao->prepare("select nome, email from bibliotecario where $email like ?");
            $stat->bindValue(1,$busca.'%');
            $stat->execute();

            $bibliotecarios = $stat->fetchAll(PDO::FETCH_CLASS, 'Bibliotecario');

            return $bibliotecarios;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Fazer Login
    public function fazerLogin($email, $senha){
        try{
            $stat = $this->conexao->prepare("select * from bibliotecario where email = ? and senha = ?");
            $stat->bindValue(1,$email);
            $stat->bindValue(2,$senha);
            $stat->execute();

            $bibliotecario = $stat->fetchObject('Bibliotecario');

            return $bibliotecario;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}
