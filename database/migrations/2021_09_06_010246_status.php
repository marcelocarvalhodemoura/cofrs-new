<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Status extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create estatus table
         */
        if(!Schema::hasTable('estatus')){
            Schema::create('estatus', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('est_nome', 35);
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
         * Drop estatus Table
         */
        Schema::drop('estatus');
    }
}
