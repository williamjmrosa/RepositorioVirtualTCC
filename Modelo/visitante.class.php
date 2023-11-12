<?php
/**
 * @author William José
 * 
 */
class Visitante{

    //Atributo
    private $email;
    private $nome;
    private $senha;

    //Construct
    public function __construct(){}

    //Método GET
    public function __get($n)
    {
        return $this->$n;
    }

    //Método SET
    public function __set($n, $v)
    {
        $this->$n = $v;
    }

    //Método toString
    public function __toString(){
        return nl2br("E-mail: $this->email
                Nome: $this->nome
                Senha: $this->senha");
    }

}
?>