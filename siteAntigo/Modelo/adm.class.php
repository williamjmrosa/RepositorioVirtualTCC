<?php
/**
* @author William José
* 
*/

class Adm{

    //Atributo
    private $email;
    private $nome;
    private $senha;

    //Construtor
    public function __construct(){ 
    }//fecha

    //Método GET
    public function __get($a){
        return $this->$a;
    }

    //Método SET
    public function __set($a, $v){
        $this->$a = $v;
    }

    //Método toString
    public function __toString(){
        return nl2br("E-mail: $this->email
                Nome: $this->nome
                Senha: $this->senha");
    }

}

?>