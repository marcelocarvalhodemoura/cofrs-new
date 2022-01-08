<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class estatus extends Seeder
{
    static $dados = [
        [1, 'Pago'],
        [2, 'Pendente'],
        [3, 'Transferido'],
        [4, 'Cancelado'],
        [5, 'Atrasado'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dado) {
            DB::table('estatus')->insert([
                'id' => $dado[0],
                'est_nome' => $dado[1],
            ]);
        }
    }
}
