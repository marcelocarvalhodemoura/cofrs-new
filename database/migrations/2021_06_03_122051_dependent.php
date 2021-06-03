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
        Schema::create('dependente', function(Blueprint $table){
            $table->increments('dep_codigoid');
            $table->string('dep_nome');
            $table->integer('dep_rg');
            $table->string('dep_cpf');
            $table->unsignedBigInteger('usr_codigoid');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('dependente', function(Blueprint $table){
            $table->foreign('usr_codigoid')
                ->references('usr_codigoid')
                ->on('usuario')
                ->onDelete('cascate');
        });
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
        Schema::dropIfExists('agente');
    }
}
