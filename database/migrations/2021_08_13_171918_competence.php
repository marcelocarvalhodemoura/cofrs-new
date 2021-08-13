<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Competence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('competencia', function(Blueprint $table){
            $table->increments('id');
            $table->date('com_datainicio');
            $table->date('com_datafinal');
            $table->string('com_nome');
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
         * Drop Lancamento Table
         */
        Schema::dropIfExists('competencia');
    }
}
