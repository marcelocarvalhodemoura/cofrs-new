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
        if(!Schema::hasTable('usuario')){
            Schema::create('usuario', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('usr_nome', 35);
                $table->string('usr_usuario', 35);
                $table->string('usr_senha');
                $table->string('usr_email', 45);
                $table->integer('tipusr_codigoid');
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
         * Drop Usuario Table
         */
        Schema::dropIfExists('usuario');
    }
}
