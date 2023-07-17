<?php

/**
 * @author William José
 * 
 */

Class Padronizacao{
    
    //Função que padroniza o nome
    public static function padronizarNome($v){
        return ucwords(strtolower($v));
    }

    //Função que padroniza o email
    public static function padronizarEmail($v){
        return strtolower($v);
    }

    //Função que padroniza o CPF
    public static function padronizarCPF($v){
        $v = str_replace(".","",$v);
        return str_replace("-","",$v);
    }

    //Função que padroniza contato
    public static function padronizarContato($v){
        $v = str_replace("(","",$v);
        $v = str_replace(")","",$v);
        return str_replace("-","",$v);
    }
}//Fecha classe padronizar
?>