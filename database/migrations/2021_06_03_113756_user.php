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
            $table->increments('usr_codigoid');
            $table->string('usr_nome');
            $table->string('usr_usuario');
            $table->string('usr_senha');
            $table->string('usr_email');
            $table->unsignedBigInteger('tipusr_codigoid');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('usuario', function(Blueprint $table){
            /**
             * create foreing key on the table Usuario
             */
            $table->foreign('tipusr_codigoid')
                ->references('tipusur_codigoid')
                ->on('tipousuario')
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
         * Drop Usuario Table
         */
        Schema::dropIfExists('usuario');
    }
}
