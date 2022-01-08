<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usuario extends Seeder
{
    static $dados = [
        ['ADM0', 'adm0', '$2y$10$0xJzLG4LiUE36dN0Z9Raa.1zS8VRDIjTbPTvQgcc3ya77ASYClbgy', 'teste@teste.com', '1',],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$dados as $dados) {
            DB::table('usuario')->insert([
                'usr_nome' => $dados[0],
                'usr_usuario' => $dados[1],
                'usr_senha' => $dados[2],
                'usr_email' => $dados[3],
                'tipusr_codigoid' => $dados[4],
            ]);
        }
    }
}
