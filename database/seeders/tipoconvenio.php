<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipoconvenio extends Seeder
{
    static $dados = [
        [1, 'AUX. FINANCEIRO'],
        [2, 'FARMACIA '],
        [3, 'INTERNOS'],
        [4, 'JURIDICO'],
        [5, 'OPTICA'],
        [6, 'PAVIMELI'],
        [7, 'PLANO DE SAUDE'],
        [8, 'PLANO TELEFONICO'],
        [9, 'SEGURO'],
        [10, 'TAXAS'],
        [11, 'DEP.PESSOAL'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dado) {
            DB::table('tipoconvenio')->insert([
                'id' => $dado[0],
                'tipconv_nome' => $dado[1],
            ]);
        }
    }
}
