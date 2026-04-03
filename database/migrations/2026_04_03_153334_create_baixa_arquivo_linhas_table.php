<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaixaArquivoLinhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baixa_arquivo_linhas', function (Blueprint $table) {
        $table->string('id')->primary(); // ID como VARCHAR(255) conforme seu SQL
        $table->unsignedBigInteger('id_baixa_arquivo')->nullable()->index();
        $table->integer('ln');
        $table->string('contrato', 200)->nullable();
        $table->float('valorRejeitado')->nullable();
        $table->string('motivoRejeicaoDireita', 200)->nullable();
        $table->string('motivoRejeicaoEsquerda', 200)->nullable();
        $table->string('matricula', 200)->nullable();
        $table->string('referencia', 200)->nullable();
        $table->string('mensagemMelhorada', 200)->nullable();
        $table->string('rtn', 255)->nullable();
        $table->softDeletes();
        $table->timestamps();

        // Foreign Key para a tabela pai
        $table->foreign('id_baixa_arquivo')->references('id')->on('baixa_arquivo')->onDelete('no action');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baixa_arquivo_linhas');
    }
}
