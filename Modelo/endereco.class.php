<?php
/**
* @author William José
* 
*/

class Endereco{

    //Atributo
    private $idEndereco;
    private $uf;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $complemento;
    private $cep;

    //Construtor
    public function __construct(){
    }

    //Método GET
    public function __get($e)
    {
        $this->$e;
    }

    //Método SET
    public function __set($e, $v)
    {
        $this->$e = $v;
    }

    //Método toString
    public function __toString()
    {
        nl2br("ID Endereco: $this->idEndereco
                UF: $this->uf
                Cidade: $this->cidade
                Bairro: $this->bairro
                Logradouro: $this->logradouro
                Complemento: $this->complemento
                CEP: $this->cep");
    }

}

?>