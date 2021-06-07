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
            $table->increments('lanc_codigoid');
            $table->float('lanc_valortoal');
            $table->date('lanc_datavencimento');
            $table->float('lanc_valorparcela');
            $table->unsignedBigInteger('assoc_codigoid');
            $table->unsignedBigInteger('con_codigoid');
            $table->integer('lanc_contrato');
            $table->unsignedBigInteger('est_codigoid');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('lancamento', function(Blueprint $table){
            /**
             * create foreing key on the table Associado
             */
            $table->foreign('assoc_codigoid')
                ->references('assoc_codigoid')
                ->on('associado')
                ->onDelete('cascade');

            /**
             * create foreing key on the table Convenio
             */
            $table->foreign('con_codigoid')
                ->references('con_codigoid')
                ->on('convenio')
                ->onDelete('cascade');

            /**
             * create foreing key on the table Estatus
             */
            $table->foreign('est_codigoid')
                ->references('est_codigoid')
                ->on('convenio')
                ->onDelete('cascade');

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
