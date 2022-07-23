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

            Schema::create('parcelamento', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->unsignedInteger('par_numero');
                $table->float('par_valor', 8, 2);
                $table->integer('lanc_codigoid');
                $table->date('par_vencimentoparcela');
                $table->text('par_observacao');
                $table->string('par_status', 45);
                $table->integer('com_codigoid');
                $table->unsignedInteger('par_equivalente');
                $table->boolean('par_habilitasn');
                $table->softDeletes();
                $table->timestamps();
                $table->index('par_habilitasn');
                $table->index('par_status');
                $table->index('par_vencimentoparcela');
                $table->index('lanc_codigoid');
            });
            //gambiarra porque o laravel cria como double
            DB::statement('ALTER TABLE parcelamento CHANGE par_valor par_valor FLOAT');

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
