<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Associate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create associado table
         */

        Schema::create('associado', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
            $table->string('assoc_nome');
            $table->string('assoc_matricula')->nullable();
            $table->string('assoc_cpf');
            $table->string('assoc_rg');
            $table->date('assoc_datanascimento');
            $table->string('assoc_sexo');
            $table->string('assoc_profissao');
            $table->string('assoc_fone');
            $table->string('assoc_email')->nullable();
            $table->string('assoc_cep');
            $table->string('assoc_endereco');
            $table->string('assoc_complemento');
            $table->string('assoc_bairro');
            $table->string('assoc_uf', 3);
            $table->string('assoc_cidade');
            $table->text('assoc_observacao')->nullable();
            $table->integer('tipassoc_codigoid');
            $table->integer('cla_codigoid');
            $table->string('assoc_banco')->nullable();
            $table->string('assoc_agencia')->nullable();
            $table->string('assoc_conta')->nullable();
            $table->string('assoc_tipoconta');
            $table->string('assoc_estadocivil');
            $table->string('assoc_fone2')->nullable();
            $table->boolean('assoc_ativosn');
            $table->date('assoc_dataativacao');
            $table->date('assoc_datadesligamento');
            $table->string('assoc_contrato')->nullable();
            $table->string('assoc_contrato_terceiros')->nullable();
            $table->integer('ag_codigoid');
            $table->boolean('assoc_removesn');
            $table->string('assoc_identificacao');
            $table->softDeletes();
            $table->timestamps();
            $table->index('cla_codigoid');
            $table->index('assoc_ativosn');
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
         * Drop associado Table
         */
        Schema::dropIfExists('associado');
    }
}
