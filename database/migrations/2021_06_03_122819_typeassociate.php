<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Typeassociate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create Tipoassociado Table
         */
        Schema::create('tipoassociado', function(Blueprint $table){
           $table->increments('id');
           $table->string('tipassoc_nome');
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
         * Drop Tipoassociado Table
         */
        Schema::dropIfExists('tipoassociado');
    }
}
