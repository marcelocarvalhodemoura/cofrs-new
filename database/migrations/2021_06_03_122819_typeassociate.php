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
        if(!Schema::hasTable('tipoassociado')) {
            Schema::create('tipoassociado', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('tipassoc_nome', 30);
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
         * Drop Tipoassociado Table
         */
        Schema::dropIfExists('tipoassociado');
    }
}
