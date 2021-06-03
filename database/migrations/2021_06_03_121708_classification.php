<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Classification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create table Classificacao
         */

        Schema::create('classificacao', function(Blueprint $table){
           $table->increments('cla_codigoid');
           $table->string('cla_nome');
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
         * Drop Classificacao Table
         */
        Schema::dropIfExists('classificacao');
    }
}
