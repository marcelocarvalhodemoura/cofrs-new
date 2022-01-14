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
        if(!Schema::hasTable('convenio')) {
            Schema::create('convenio', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('con_nome', 45);
                $table->integer('tipconv_codigoid');
                $table->string('con_referencia');
                $table->float('con_prolabore');
                $table->softDeletes();
                $table->timestamps();
            });
        }
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
