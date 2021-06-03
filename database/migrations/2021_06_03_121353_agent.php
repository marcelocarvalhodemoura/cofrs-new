<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Agent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create table Agente
         */
        Schema::create('agente', function(Blueprint $table){
            $table->increments('ag_codigoid');
            $table->string('ag_nome');
            $table->softDeletes();
            $table->timestamps();
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
