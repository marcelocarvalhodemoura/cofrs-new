<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipousuario extends Seeder
{
    static $dados = [
        [1, 'DEV'],
        [2, 'Administrador'],
        [3, 'Auxiliar Financeiro'],
        [4, 'Operador'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dado) {
            DB::table('tipousuario')->insert([
                'id' => $dado[0],
                'tipusr_nome' => $dado[1],
            ]);
        }
    }
}
