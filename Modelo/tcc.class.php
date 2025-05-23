<?php
/**
* @author William José
* 
*/

class TCC{

    //Atributo
    private $idTCC;
    private $titulo;
    private $descricao;
    private $localPDF;
    private Curso $curso;
    private Campus $campus;
    private Aluno $aluno;
    private $orientador;
    private $categorias;

    //Construtor
    public function __construct(){
    }//fecha

    //Método GET
    public function __get($t){
        return $this->$t;
    }

    //Método SET
    public function __set($t,$v){
        $this->$t = $v;
    }

    // Ver Orientadores
    public function verOrientadores(){
        $nomes = "";
        foreach($this->orientador as $v){
            $nomes = $nomes . "<br>" . $v->nome;
        }

        return $nomes;
    }

    //Ver Categorias
    public function verCategorias(){
        $nomes = "";
        foreach($this->categorias as $v){
            ($nomes == "") ? $nomes = $v->nome : $nomes = $nomes . "," . $v->nome;
        }
    
        return $nomes;
    }

    //Método toString
    public function __toString(){
        return nl2br("ID TCC: $this->idTCC
                Titulo: $this->titulo
                Descrição: $this->descricao
                Local PDF: $this->localPDF
                Curso: $this->curso
                Campus: $this->campus
                Aluno: $this->aluno
                Categorias: $this->categorias");
    }

}

?>