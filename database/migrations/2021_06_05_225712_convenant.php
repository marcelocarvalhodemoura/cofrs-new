<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Convenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamento', function(Blueprint $table){
            $table->increments('id');
            $table->float('lanc_valortoal');
            $table->date('lanc_datavencimento');
            $table->float('lanc_valorparcela');
            $table->integer('assoc_codigoid');
            $table->integer('con_codigoid');
            $table->integer('lanc_contrato');
            $table->unsignedBigInteger('est_codigoid');
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
        Schema::drop('lancamento');
    }
}
