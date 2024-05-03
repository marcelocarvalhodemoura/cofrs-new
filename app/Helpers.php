<?php

namespace App\Helpers;
/*
if(!function_exists('permissions')){
    function permissions($nameControler){
        die('OI');
    }
}
*/

if(!function_exists('formataCPF')){
    function formataCPF($cpf){
        if (!$cpf) {
            return '';
        }
        $cpf = str_pad(limpaNumero($cpf),11,'0',STR_PAD_LEFT);
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);

        return $cpf;
    }
}

if(!function_exists('limpaNumero')){
    function limpaNumero($numero){
        if(!$numero){
            return '';
        }
        $numero = preg_replace("/[^0-9]/", "", $numero); 

        return $numero;
    }
}
