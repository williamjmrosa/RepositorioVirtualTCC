<?php
/**
 * @author William José
 * 
 */

Class Validacao{
    
    public static function validarNome($v){
        $exp = '/^[A-záéíóúÁÉÍÓÚãõüÃÕÇçôÔ]{2,20}([ ]?[A-záéíóúÁÉÍÓÚãõüÃÕÇçôÔ]{1,20}){1,8}$/';
        return preg_match($exp, $v);
    }

    public static function validarSenha($v){
        $exp ='/^.{6,25}$/';
        return preg_match($exp, $v);
    }
    public static function validarEmail($v){
        return filter_var($v, FILTER_VALIDATE_EMAIL);
    }

    public static function confirmarEmail($e, $e1){
        if($e == $e1){
            return true;
        }else{
            return false;
        }
    }
    public static function validarCPF($v){
        $exp = '/^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/';
        return preg_match($exp, $v);
    }

    public static function validarContato($v) {
    	$exp = '/^[(][0-9]{2,3}[)][ ][0-9]{4,5}-[0-9]{4}$/';
    	return preg_match($exp, $v);
    }
}

?>