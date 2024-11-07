<?php
/**
 * @author William José
 * 
 */

Class Validacao{
    
    private const PESO_10 = 10;
    private const PESO_11 = 11;

    //Função que valida numero de caracteres
    public static function validarTamanho($v, $t){
        return strlen($v) <= $t;
    }

    //Função que valida o nome
    public static function validarNome($v){
        $exp = '/^[A-záéíóúÁÉÍÓÚãõâÂüÃÕÇçôÔêÊ]{2,20}([ ]?[A-záéíóúÁÉÍÓÚâÂãõüÃÕÇçôÔÊê]{1,20}){1,8}$/';
        return preg_match($exp, $v);
    }
    //Função que valida categoria
    public static function validarCategoria($v){
        $exp = '/^[A-záéíóúÁÉÍÓÚãõüÃÕÇçôÔêÊ]{2,20}([ ]?[A-záéíóúÁÉÍÓÚãõüÃâÂÕÇçôÔêÊê]{1,20}){0,8}$/';
        return preg_match($exp, $v);
    }

    //Função que valida se arquivo é PDF
    public static function validarPDF($file){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file);
        
        if ($mimeType == 'application/pdf') {
            $resposta = true;
        } else {
            $resposta = false;
        }
        
        finfo_close($finfo);

        return $resposta;
    }

    //Função que valida senha
    public static function validarSenha($senha) {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,20}$/';
        if (preg_match($pattern, $senha)) {
            return true;
        } else {
            return false;
        }
    }

    //Função que valida email
    public static function validarEmail($v){
        return filter_var($v, FILTER_VALIDATE_EMAIL);
    }

    //Função que confirmar email
    public static function confirmarEmail($e, $e1){
        if($e == $e1){
            return true;
        }else{
            return false;
        }
    }

    // Função que valida um CPF
    public static function validarCPF($cpf){
        // Remover caracteres não numéricos do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
        // Verificar se o CPF possui 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }
    
        // Verificar se todos os dígitos são iguais (CPF inválido)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // Calcular o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += intval($cpf[$i]) * (10 - $i);
        }
        $resto = $soma % 11;
        $digitoVerificador1 = ($resto < 2) ? 0 : 11 - $resto;
    
        // Verificar o primeiro dígito verificador
        if (intval($cpf[9]) !== $digitoVerificador1) {
            return false;
        }
    
        // Calcular o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += intval($cpf[$i]) * (11 - $i);
        }
        $resto = $soma % 11;
        $digitoVerificador2 = ($resto < 2) ? 0 : 11 - $resto;
    
        // Verificar o segundo dígito verificador
        if (intval($cpf[10]) !== $digitoVerificador2) {
            return false;
        }
    
        // CPF válido
        return true;
    }

    // Função que valida o RG
    public static function validarRG($rg){
        $rgSemFormatacao = Padronizacao::padronizarCPF_RG($rg);
        $rgCom8PrimeirosDigitos = substr($rgSemFormatacao, 0, 8);
        $cont = 9;
        $soma = 0;
    
        for($i = 0; $i < strlen($rgCom8PrimeirosDigitos); $i++){
            $soma += $rgCom8PrimeirosDigitos[$i] * $cont;
            $cont--;
        }
    
        $dv = $soma % 11;
    
        if($dv == 10){
            // Se o resto da divisão for 10, o dígito verificador deve ser X
            return $rgSemFormatacao[8] == 'X';
        }else{
            // Caso contrário, o dígito verificador deve ser igual ao resultado da divisão
            return $rgSemFormatacao[8] == $dv;
        }
    }

    // Função que valida um número de telefone
    public static function validarContato($telefone){
        // Remover caracteres não numéricos do telefone
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
        // Verificar se o telefone possui um número mínimo de dígitos
        if (strlen($telefone) < 7) {
            return false;
        }
    
        // Outras regras de validação podem ser adicionadas aqui, dependendo dos padrões do seu sistema.
        // Por exemplo, você pode verificar se o telefone possui um código de área válido, etc.
    
        // Telefone válido
        return true;
    }
}

?>