<?php

/**
* @author William José
* 
*/

class Campus{

    //Atributo
    private $idCampus;
    private $nome;
    private $cursos;

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

    //Método adicionarCurso
    public function addCurso($curso){
        $this->cursos[] = $curso;
    }

    //Método toString
    public function __toString(){
        nl2br("ID Campus: $this->idCampus
                Nome: $this->nome
                Cursos: Curso");
    }

}

?>