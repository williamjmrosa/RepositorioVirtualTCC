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

    //Função que padroniza em maiusculas
    public static function padronizarMaiusculas($v){
        return strtoupper($v);
    }

    //Função que padroniza o email
    public static function padronizarEmail($v){
        return strtolower($v);
    }

    //Função que padroniza o CPF
    public static function padronizarCPF_RG($v){
        return str_replace([".", "-"], "", $v);
    }

    //Função que padroniza contato
    public static function padronizarContato($v){
        return str_replace(["(", ")"," ","-"],"", $v);
    }

    // Função que padroniza um CEP
    public static function padronizarCEP($cep){
        // Remover caracteres não numéricos do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);
    
        // Verificar se o CEP possui a quantidade correta de dígitos
        if (strlen($cep) !== 8) {
            return false;
        }
    
        // Adicionar zeros à esquerda, se necessário
        $cep = str_pad($cep, 8, '0', STR_PAD_LEFT);
    
        // Retornar o CEP padronizado
        return $cep;
    }
}//Fecha classe padronizar
?>