<?php

namespace App\Helpers;

if(!function_exists('permissions')){
    function permissions($nameControler){
        die('OI');
    }
}

if(!function_exists('formataCPF')){
    function formataCPF($cpf){
        if (!$cpf) {
            return '';
        }
        $cpf = str_pad($cpf,11,'0',STR_PAD_LEFT);
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);

        return $cpf;
    }
}
