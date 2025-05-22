<?php
/**
* @author William José
* 
*/

class Bibliotecario{
    
    //Atributo
    private $email;
    private $nome;
    private $senha;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($b){
        return $this->$b;
    }

    //Método SET
    public function __set($b, $v){
        $this->$b = $v;
    }

    //Método toString
    public function __toString(){
        return nl2br("Nome: $this->nome
                E-mail: $this->email
                Senha: $this->senha");
    }

}

?>