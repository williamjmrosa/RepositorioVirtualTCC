<?php
/**
* @author William José
* 
*/
class Professor{

    //Atributo
    private $matricula;
    private $nome;
    private $rg;
    private $cpf;
    private $telefone;
    private $email;
    private $senha;
    private Endereco $end;
    private $ativo;

    //Construtor
    public function __construct(){
    }//fecha


    //Método GET
    public function __get($p)
    {
        return $this->$p;
    }

    //Método SET
    public function __set($p, $v)
    {
        $this->$p = $v;
    }

    public function mostrarStatus(){
        if($this->ativo == 1){
            return 'Inativo';
        }else{
            return 'Ativo';
        }
    }

    //Método toString
    public function __toString()
    {
        return nl2br("Matricula: $this->matricula
                Nome: $this->nome
                RG: $this->rg
                CPF: $this->cpf
                Telefone: $this->telefone
                E-mail: $this->email
                Senha: $this->senha
                Endereço: $this->end");
    }

}
?>