<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipousuario extends Seeder
{
    static $dados = [
        [1, 'DEV'],
        [2, 'ADM'],
        [5, 'Auxiliar Financeiro'],
        [6, 'Auxiliar ADM'],
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
