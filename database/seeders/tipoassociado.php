<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class tipoassociado extends Seeder
    {
        static $dados = [
            [1, 'ALTRUISTA'],
            [2, 'MUTUALISTA'],
            [3, 'BENEFICIARIO'],
        ];
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            foreach (self::$dados as $dado) {
                DB::table('tipoassociado')->insert([
                    'id' => $dado[0],
                    'tipassoc_nome' => $dado[1],
                ]);
            }
        }
    }
