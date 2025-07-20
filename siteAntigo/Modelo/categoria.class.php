<?php
/**
* @author William José
* 
*/

class Categoria{

    //Atributo
    private $idCategoria;
    private $nome;
    private $eSub;
    private $categoriaPrincipal;
    private $nomeAlternativo;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($c){
        return $this->$c;
    }

    //Método SET
    public function __set($c,$v){
        $this->$c = $v;
    }

    //Método toString
    public function __toString(){
        return nl2br("ID Categoria: $this->idCategoria
                Nome: $this->nome
                É subCategoria: $this->eSub
                ID Categoria Principal: $this->categoriaPrincipal
                Nome Alternativo: ".$this->nomeAlternativo[0]);
    }

}

?>