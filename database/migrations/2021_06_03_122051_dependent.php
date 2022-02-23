<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dependent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create Dependente Table
         */

        Schema::create('dependente', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
            $table->string('dep_nome', 30);
            $table->string('dep_fone', 35);
            $table->integer('dep_rg', false, false);
            $table->string('dep_cpf', 20);
            $table->integer('assoc_codigoid');
            $table->softDeletes();
            $table->timestamps();
        });
        //gambiarra porque o laravel não tem método nem parâmetro pra setar length
        DB::statement('ALTER TABLE dependente CHANGE dep_rg dep_rg INT(20)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * Drop Agente Table
         */
        Schema::dropIfExists('dependente');
    }
}
