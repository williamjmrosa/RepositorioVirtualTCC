<?php
/**
 * @author William José
 * 
 */

class Aluno{

    //Atributo
    private $matricula;
    private $nome;
    private $rg;
    private $cpf;
    private $telefone;
    private $email;
    private $senha;
    private Endereco $end;
    private $campus;
    private $curso;

    //Construtor
    public function __construct(){
    }//fecha construtor

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
        return nl2br("Matricula: $this->matricula
                    Nome: $this->nome
                    RG: $this->rg
                    CPF: $this->cpf
                    Telefone: $this->telefone
                    E-mail: $this->email
                    Senha: $this->senha
                    Campus: $this->campus
                    Curso: $this->curso
                    Endereço: $this->end");
    }

}

?>