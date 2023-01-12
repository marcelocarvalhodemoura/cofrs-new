<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLancContratoLancamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lancamento', function (Blueprint $table) {
            $table->string('lanc_contrato')->after('est_codigoid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lancamento', function (Blueprint $table) {
            $table->dropColumn('lanc_contrato');
        });
    }
}
