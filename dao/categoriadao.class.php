<?php
require '../persistencia/conexaobanco.class.php';

class CategoriaDAO{
    private $conexao = null;
    
    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    public function cadastrarCategoria(Categoria $categoria){
        try{
            $stat = $this->conexao->prepare("Insert into categoria(idCategoria,nome,eSub,categoriaPrincipal) values (null,?,?,?)");

            $stat->bindValue(1,$categoria->nome);
            $stat->bindValue(2,$categoria->eSub);
            $stat->bindValue(3,$categoria->categoriaPrincipal);
            $stat->execute();

            $categoria->idCategoria = $this->conexao->lastInsertId();

            foreach($categoria->nomeAlternativo as $v){
                $this->cadastrarNomeAlternativo($v,$categoria->idCategoria);                
            }

        } catch (PDOException $ex){
            $_SESSION['erros'] =  $ex->getMessage();
        }
    }

    public function listarCategoria(){
        try{
            $stat = $this->conexao->prepare("Select * from categoria");
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    public function cadastrarNomeAlternativo($nomeAlternativo,$idCategoria){
        try{
            $stat = $this->conexao->prepare("Insert into nomeAlternativo(idCategoria,nomeAlternativo) values (?,?)");
            $stat->bindValue(1,$idCategoria);
            $stat->bindValue(2,$nomeAlternativo);
            $stat->execute();
            
            return true;

        } catch (PDOException $ex){
            $_SESSION['erros'] =  $ex->getMessage();
        }
    }
    
    public function alterarCategoria(Categoria $categoria){
        try{
            $stat = $this->conexao->prepare("Update categoria set nome =?, eSub =?, categoriaPrincipal =? where idCategoria =?");
            $stat->bindValue(1,$categoria->nome);
            $stat->bindValue(2,$categoria->eSub);
            $stat->bindValue(3,$categoria->categoriaPrincipal);
            $stat->bindValue(4,$categoria->idCategoria);
            $stat->execute();
            
        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }
}
?>