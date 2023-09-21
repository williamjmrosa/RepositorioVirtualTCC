<?php
require '../persistencia/conexaobanco.class.php';

/**
 * @author William José
 */
class EnderecoDAO{

    private $conexao = null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }

    //Cadastrar um Endereço
    public function cadastrarEndereco(Endereco $endereco) {
        try{
            $stat = $this->conexao->prepare("insert into endereco(idEndereco,bairro,logradouro,cep,uf,cidade,complemento) values(null,?,?,?,?,?,?)");

            $stat->bindValue(1,$endereco->bairro);
            $stat->bindValue(2,$endereco->logradouro);
            $stat->bindValue(3,$endereco->cep);
            $stat->bindValue(4,$endereco->uf);
            $stat->bindValue(5,$endereco->cidade);
            $stat->bindValue(6,$endereco->complemento);

            $stat->execute();

            $id = $this->conexao->lastInsertId();

            return $id;

        }catch(PDOException $ex){
            return $ex->getMessage();
        }
    }

}
?>