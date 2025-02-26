<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConComissaoCofrsToConvenioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenio', function (Blueprint $table) {
            $table->decimal('con_comissao_cofrs', 10, 2)->default(0)->after('con_nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenio', function (Blueprint $table) {
            $table->dropColumn('con_comissao_cofrs');
        });
    }
}
