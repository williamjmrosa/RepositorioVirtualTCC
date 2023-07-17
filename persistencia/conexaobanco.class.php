<?php
Class ConexaoBanco extends PDO{
    
    private static $instancia  = null;

    public function __construct($dsn,$usuario,$senha){
        //Construtor de Classe Pai PDO
        parent::__construct($dsn,$usuario,$senha);
    }

    public static function getInstancia(){
        if(!isset(self::$instancia)){
           try {
             /*Cria e retorna uma nova conexão*/

            self::$instancia = new ConexaoBanco("mysql:dbname=RepositorioVirtualTCC;host=localhost","root","");


           } catch (Exception $e) {
                echo "Erro ao Conectar";
                exit();
           }//Fecha catch
        }//fecha if

        return self::$instancia;
    }//fehca método

}//fecha classe
?>