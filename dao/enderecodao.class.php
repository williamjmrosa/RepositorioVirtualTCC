<?php
require_once '../persistencia/conexaobanco.class.php';
include_once '../Modelo/endereco.class.php';

/**
 * @author William José
 */
class EnderecoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    //Cadastrar um Endereço
    public function cadastrarEndereco(Endereco $endereco){
        try{
            $stat = $this->conexao->prepare("Insert into endereco(idEndereco,cep,logradouro,bairro,cidade,uf,complemento) values (null,?,?,?,?,?,?)");
            
            $stat->bindValue(1,$endereco->cep);
            $stat->bindValue(2,$endereco->logradouro);
            $stat->bindValue(3,$endereco->bairro);
            $stat->bindValue(4,$endereco->cidade);
            $stat->bindValue(5,$endereco->uf);
            $stat->bindValue(6,$endereco->complemento);
            
            $stat->execute();

            $endereco->idEndereco = $this->conexao->lastInsertId();
            
            return $endereco->idEndereco;


        } catch (PDOException $ex){
            echo $ex->getMessage();//$_SESSION['erros'] =  $ex->getMessage();
        }
    }

    // Buscar Endereço por ID
    public function encontrarEnderecoPorId($id){
        try{
            $stat = $this->conexao->prepare("select * from Endereco where idEndereco = ?");
            $stat->bindValue(1,$id);
            $stat->execute();

            $endereco = $stat->fetch(PDO::FETCH_ASSOC);

            return $endereco;

        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
        
    }

    // Alterar Endereço
    public function alterarEndereco(Endereco $endereco){
        try{
            echo "entrou";
            $stat = $this->conexao->prepare("update Endereco set cep = ?, logradouro = ?, bairro = ?, cidade = ?, uf = ?, complemento = ? where idEndereco = ?");

            $stat->bindValue(1,$endereco->cep);
            $stat->bindValue(2,$endereco->logradouro);
            $stat->bindValue(3,$endereco->bairro);
            $stat->bindValue(4,$endereco->cidade);
            $stat->bindValue(5,$endereco->uf);
            $stat->bindValue(6,$endereco->complemento);
            $stat->bindValue(7,$endereco->idEndereco);

            $stat->execute();

            return true;
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

}
?>