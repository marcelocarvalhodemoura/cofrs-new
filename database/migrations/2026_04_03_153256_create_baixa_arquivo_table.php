<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaixaArquivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baixa_arquivo', function (Blueprint $table) {
        $table->id(); // BIGINT AUTO_INCREMENT
        $table->string('extensionArchive', 4);
        $table->string('competencia', 6);
        $table->string('convenio', 20);
        $table->unsignedInteger('usuario_id')->index(); // FK para usuario
        $table->integer('processado')->default(0)->index();
        $table->string('tempo', 100)->nullable();
        $table->softDeletes(); // deleted_at
        $table->timestamps(); // created_at e updated_at

        // Foreign Key (Verifique se a tabela 'usuario' já existe)
        $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('no action');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baixa_arquivo');
    }
}
