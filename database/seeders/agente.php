<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class agente extends Seeder
{
    static $dados = [
        [1, 'COFRS'],
        [2, 'PAVIMELI'],
        [3, 'FRONT BRASIL'],
        [4, 'BMDC'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dado) {
            DB::table('agente')->insert([
                'id' => $dado[0],
                'ag_nome' => $dado[1],
            ]);
        }
    }
}
