<?php
/**
* @author William José
* 
*/

class Favoritos{

    //Atributo
    private $idFavorito;
    private $idTCC;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($f){
        return $this->$f;
    }

    //Método SET
    public function __set($f, $v){
        $this->$f = $v;
    }

    //Método toString
    public function __toString(){
        nl2br("ID Favorito: $this->idFavorito
                ID TCC: $this->idTCC ");
    }

}

?>