<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class classificacao extends Seeder
{
    static $dados = [
        [3, 'ASSOCIADO ALTRUISTA'],
        [4, 'ASSOCIADO MUTUALISTA'],
        [9, 'CIRCULOS OPERARIOS/FEDERACOES'],
        [10, 'DEPENDENTES'],
        [11, 'FUNCIONARIOS COFRS'],
        [12, 'PARCERIAS'],
        [13, 'PODER JUDICIARIO'],
        [14, 'PARTICULAR COFSM'],
        [15, 'IPERGS'],
        [16, 'RFFSA'],
        [17, 'RPA'],
        [18, 'TESOURO DO ESTADO'],
        [19, 'PARTICULAR COFRS'],
        [20, 'PROJETO VIRA VIDA'],
        [21, 'PRESTADORES DE SERVICO'],
        [22, 'LOCATARIOS'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dado) {
            DB::table('classificacao')->insert([
                'id' => $dado[0],
                'cla_nome' => $dado[1],
            ]);
        }
    }
}
