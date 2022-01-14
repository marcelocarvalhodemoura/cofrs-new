<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Banks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('banks')) {
            Schema::create('banks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name_bank');
                $table->integer('bank_agency');
                $table->integer('bank_account');
                $table->boolean('bank_status')->default(1)->nullable(false);
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
        Schema::dropIfExists('banks');
    }
}
