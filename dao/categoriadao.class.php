<?php
require_once '../persistencia/conexaobanco.class.php';

class CategoriaDAO{
    private $conexao = null;
    
    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    // Cadastrar Categoria
    public function cadastrarCategoria(Categoria $categoria){
        try{
            $stat = $this->conexao->prepare("Insert into categoria(idCategoria,nome,eSub,categoriaPrincipal) values (null,?,?,?)");

            $stat->bindValue(1,$categoria->nome);
            $stat->bindValue(2,$categoria->eSub);
            $stat->bindValue(3,$categoria->categoriaPrincipal);
            $stat->execute();

            $categoria->idCategoria = $this->conexao->lastInsertId();

            if(!empty($categoria->nomeAlternativo)){
                foreach($categoria->nomeAlternativo as $v){
                    $this->cadastrarNomeAlternativo($v,$categoria->idCategoria);
                }
            }

        } catch (PDOException $ex){
            $_SESSION['erros'] =  $ex->getMessage();
        }
    }
    // Exlcuir Nome Alternativo
    public function excluirNomeAlternativo($id){
        try{
            $stat = $this->conexao->prepare("Delete from nomealternativo where idNomeAlternativo = ?");
            
            $stat->bindValue(1,$id);
            
            $stat->execute();
            
            return true;
            
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }
    
    // Excluir Categoria
    public function excluirCategoria($id){
        try{
            
            if($this->excluirCategorias($id)){

                if($this->alterarCategoriaPrincipalParaNull($id)){
                    $stat = $this->conexao->prepare("Delete from categoria where idCategoria = ?");
                
                    $stat->bindValue(1,$id);
                
                    $stat->execute();

                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }

    // Excluir Categorias
    public function excluirCategorias($id){
        try{
            $stat = $this->conexao->prepare("Delete from categorias where idCategoria = ?");
            
            $stat->bindValue(1,$id);
            
            $stat->execute();

            return true;
            
        }catch(PDOException $ex){
            //echo $ex->getMessage();
            return false;
        }
    }

    // Alterar Categoria Principal para null ao deletar
    public function alterarCategoriaPrincipalParaNull($id){
        try{
            $stat = $this->conexao->prepare("Update categoria set categoriaPrincipal = null, eSub = 0 where categoriaPrincipal = ?");
            
            $stat->bindValue(1,$id);
            
            $stat->execute();

            return true;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return false;
        }
    }

    // Listar Categorias
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

    // Listar Categorias principais
    public function listarCategoriaPrincipal(){
        try{
            $stat = $this->conexao->prepare("Select * from categoria where eSub = 0");
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Categorias principal por nome
    public function buscarCategoriaPrincipalPorNome($nome){
        try{
            $stat = $this->conexao->prepare("Select c.idCategoria, c.nome, c.eSub, c.categoriaPrincipal from categoria as c join nomealternativo as na on c.idCategoria = na.idCategoria where eSub = 0 and nome like ? or nomeAlternativo like ? UNION Select idCategoria, nome, eSub, categoriaPrincipal from categoria where eSub = 0 and nome like ?");

            $stat->bindValue(1,$nome."%");
            $stat->bindValue(2,$nome."%");
            $stat->bindValue(3,$nome."%");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Listar Categorias secundarias
    public function listarSubCategorias(){
        try{
            $stat = $this->conexao->prepare("Select * from categoria where eSub = 1");
            
            $stat->execute();
            
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Listar Categorias secundarias por categoria principal
    public function listarSubCategoriasPelaPrincipal($id){
        try{
            $stat = $this->conexao->prepare("Select * from categoria where eSub = 1 and categoriaPrincipal = ?");
            
            $stat->bindValue(1,$id);

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Categorias secundarias por nome
    public function buscarSubCategoriasPorNome($nome){
        try{
            $stat = $this->conexao->prepare("Select * from categoria as c inner join nomealternativo as na on c.idCategoria = na.idCategoria where eSub = 1 and nome like ? or nomeAlternativo like ?");

            $stat->bindValue(1,"%".$nome."%");

            $stat->bindValue(2,"%".$nome."%");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Buscar Categorias secundarias por nome relacionado a categoria principal
    public function buscarSubCategoriasPorNomeRelacionadoPrincipal($nome,$id){
        try{
            $stat = $this->conexao->prepare("Select c.idCategoria, c.nome, c.eSub, c.categoriaPrincipal from categoria as c inner join nomealternativo as na on c.idCategoria = na.idCategoria where c.categoriaPrincipal IN ($id) and eSub = 1 and nome like ? or nomeAlternativo like ? UNION Select idCategoria, nome, eSub, categoriaPrincipal from categoria where eSub = 1 and categoriaPrincipal IN ($id) and nome like ?");

            //$stat->bindValue(1,$id);
            $stat->bindValue(1,"%".$nome."%");
            $stat->bindValue(2,"%".$nome."%");
            //$stat->bindValue(4,$id);
            $stat->bindValue(3,"%".$nome."%");
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }

    }

    // Buscar Categorias por nome
    public function buscarCategoriasPorNome($nome){
        try{
            $stat = $this->conexao->prepare("Select c.idCategoria, c.nome, c.eSub, c.categoriaPrincipal from categoria as c left join nomealternativo as na on c.idCategoria = na.idCategoria where nome like ? or na.nomeAlternativo like ? group by c.idCategoria");

            $stat->bindValue(1,"%".$nome."%");
            $stat->bindValue(2,"%".$nome."%");

            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Categoria');

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }

    }

    // Buscar Categoria por idCategoria
    public function buscarCategoriasPorId($id){
        try{
            $stat = $this->conexao->prepare("Select * from categoria where idCategoria = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            $categoria = $stat->fetch(PDO::FETCH_ASSOC);
            if(is_array($categoria)){
                
                $categoria['nomeAlternativo'] = $this->buscarNomeAlternativoPorId($categoria['idCategoria']);
            
            }
            return $categoria;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    // Burcar nomeAlternativo por idCategoria
    public function buscarNomeAlternativoPorId($id){
        try{
            $stat = $this->conexao->prepare("Select * from nomealternativo where idCategoria = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            $array = $stat->fetchAll(PDO::FETCH_ASSOC);

            return $array;

        }catch(PDOException $ex){
            echo $ex->getMessage();
            return null;
        }
    }

    public function cadastrarNomeAlternativo($nomeAlternativo,$idCategoria){
        try{
            $stat = $this->conexao->prepare("Insert into nomealternativo(idCategoria,nomeAlternativo) values (?,?)");
            $stat->bindValue(1,$idCategoria);
            $stat->bindValue(2,$nomeAlternativo);
            $stat->execute();
            
            return true;

        } catch (PDOException $ex){
            echo  $ex->getMessage();
        }
    }
    
    public function alterarCategoria(Categoria $categoria){
        try{
            
            $stat = $this->conexao->prepare("Update categoria set nome =?, eSub =?, categoriaPrincipal =? where idCategoria =?");
            $stat->bindValue(1,$categoria->nome);
            $stat->bindValue(2,$categoria->eSub);
            $stat->bindValue(3,$categoria->categoriaPrincipal);
            $stat->bindValue(4,$categoria->idCategoria);
            
            if(count($categoria->nomeAlternativo) > 0){
                
                foreach($categoria->nomeAlternativo as $v){
                    $this->cadastrarNomeAlternativo($v,$categoria->idCategoria);
                }
            }

            $stat->execute();
            
        } catch (PDOException $ex){
            echo $ex->getMessage();
        }
    }

    public function alterarNomeAlternativo($nomeAlternativo,$idNomeAlternativo){
        try{
            $stat = $this->conexao->prepare("Update nomealternativo set nomeAlternativo =? where idNomeAlternativo =?");
            $stat->bindValue(1,$nomeAlternativo);
            $stat->bindValue(2,$idNomeAlternativo);
            $stat->execute();

            return true;
            
        } catch (PDOException $ex){
            return $ex->getMessage();
        }
    }
}
?>