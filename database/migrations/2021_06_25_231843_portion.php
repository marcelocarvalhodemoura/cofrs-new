<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Portion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelamento', function(Blueprint $table){
            $table->increments('id');
            $table->integer('par_numero');
            $table->float('par_valor');
            $table->integer('lanc_codigoid');
            $table->text('par_observacao');
            $table->string('par_status');
            $table->integer('com_codigoid');
            $table->integer('par_equivalente');
            $table->tinyInteger('par_habilitash');
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
        Schema::dropIfExists('parcelamento');
    }
}
