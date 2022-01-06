<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(agente::class);
        $this->call(classificacao::class);
        $this->call(tipoconvenio::class);
        $this->call(estatus::class);
        $this->call(tipousuario::class);
        $this->call(usuario::class);
    }
}
