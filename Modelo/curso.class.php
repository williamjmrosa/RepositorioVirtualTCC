<?php
/**
* @author William José
* 
*/

class Curso{

    //Atributo
    private $idCurso;
    private $nome;
    private $ensino;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($c){
        return $this->$c;
    }

    //Método SET
    public function __set($c, $v){
        $this->$c = $v;
    }

    //Função ensino
    public function mostrarEnsino(){
        if($this->ensino == 0){
            return "Ensino Médio";
        }else{
            return "Ensino Superior";
        }
    }

    //Método toString
    public function __toString(){
        return nl2br("ID Curso: $this->idCurso
            Nome: $this->nome
            Ensino "+ $this->mostrarEnsino()+"");
    }

}

?>