<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create Usuario table
         */
        Schema::create( 'usuario', function(Blueprint $table){
            $table->increments('id');
            $table->string('usr_nome');
            $table->string('usr_usuario');
            $table->string('usr_senha');
            $table->string('usr_email');
            $table->integer('tipusr_codigoid');
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
         * Drop Usuario Table
         */
        Schema::dropIfExists('usuario');
    }
}
