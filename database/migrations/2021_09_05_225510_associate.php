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
        Schema::create( 'associado', function(Blueprint $table){
            $table->increments('id');
            $table->string('assoc_nome');
            $table->string('assoc_rg');
            $table->string('assoc_sexo');
            $table->string('assoc_profissao');
            $table->string('assoc_matricula');
            $table->string('assoc_cpf');
            $table->date('assoc_datanascimento');
            $table->string('assoc_fone');
            $table->string('assoc_email');
            $table->string('assoc_cep');
            $table->string('assoc_endereco');
            $table->string('assoc_complemento');
            $table->string('assoc_bairro');
            $table->string('assoc_uf');
            $table->string('assoc_cidade');
            $table->text('assoc_observacao');
            $table->integer('tipassoc_codigoid');
            $table->integer('cla_codigoid');
            $table->string('assoc_banco');
            $table->string('assoc_agencia');
            $table->string('assoc_conta');
            $table->string('assoc_tipoconta');
            $table->string('assoc_fone2');
            $table->string('assoc_estadocivil');
            $table->boolean('assoc_ativosn');
            $table->date('assoc_dataativacao');
            $table->date('assoc_dataassociado');
            $table->date('assoc_datadesligamento');
            $table->string('assoc_contrato');
            $table->integer('ag_codigoid');
            $table->boolean('assoc_removesn');
            $table->string('assoc_identificacao');
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
        /**
         * Drop associado Table
         */
        Schema::dropIfExists('associado');
    }
}
