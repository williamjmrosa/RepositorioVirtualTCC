<?php
/**
* @author William José
* 
*/

class Indicacao{

    //Atributo
    private $idIndicacao;
    private $idCurso;
    private $idInstituicao;
    private $matricula;
    private $idTCC;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($i){
        return $this->$i;
    }

    //Método SET
    public function __set($i,$v){
        $this->$i = $v;
    }

    //Método toString
    public function __toString(){
        nl2br("ID Indicação: $this->idIndicacao
                ID Curso: $this->idCurso
                ID Instituição: $this->idInstituicao
                Matricula: $this->matricula
                ID TCC: $this->idTCC");
    }

}

?>