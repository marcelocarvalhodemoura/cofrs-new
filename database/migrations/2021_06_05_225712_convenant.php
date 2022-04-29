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
        Schema::create('lancamento', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
            $table->float('lanc_valortotal', 0, 0);
            $table->integer('lanc_numerodeparcela');
            $table->date('lanc_datavencimento');
            $table->integer('con_codigoid');
            $table->integer('assoc_codigoid');
            //$table->integer('est_codigoid', false, false);
            $table->string('lanc_contrato', 255);
            $table->softDeletes();
            $table->timestamps();
        });
        //gambiarra porque o laravel cria como double
        DB::statement('ALTER TABLE lancamento CHANGE lanc_valortotal lanc_valortotal FLOAT NOT NULL');

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
        Schema::dropIfExists('lancamento');
    }
}
