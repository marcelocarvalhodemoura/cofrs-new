<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Installment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create convenio table
         */
        Schema::create( 'convenio', function(Blueprint $table){
            $table->increments('id');
            $table->string('con_nome');
            $table->integer('tipconv_codigoid');
            $table->string('con_referencia');
            $table->float('con_prolabore');
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
         * Drop convenio Table
         */
        Schema::dropIfExists('convenio');
    }
}
