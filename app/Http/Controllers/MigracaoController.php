<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

/**
 * Class BanksController
 * @package App\Http\Controllers
 */
class MigracaoController extends Controller{

  private $origem;

  public function __construct(){
    \Config::set("database.connections.cofrs_old", [
      "driver" => "mysql",
      "host" => "localhost",
      "database" => "cofrs_old",
      "username" => "root",
      "password" => ""
  ]);
    $this->origem = DB::connection('cofrs_old')->getPdo();
  }

  public function index(){
    //agente
    DB::statement('truncate table agente');
    $sql = "INSERT INTO agente (id, ag_nome) values ";
    $dados = $this->origem->query("SELECT * FROM agente");
    foreach($dados as $v){
      $sql .= " (".$v['ag_codigoid'].", '".$v['ag_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela agente migrada </br />';

    //classificacao
    DB::statement('truncate table classificacao');
    $sql = "INSERT INTO classificacao (id, cla_nome) values ";
    $dados = $this->origem->query("SELECT * FROM classificacao");
    foreach($dados as $v){
      $sql .= " (".$v['cla_codigoid'].", '".$v['cla_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela classificação migrada </br />';

    //tipoassociado
    DB::statement('truncate table tipoassociado');
    $sql = "INSERT INTO tipoassociado (id, tipassoc_nome) values ";
    $dados = $this->origem->query("SELECT * FROM tipoassociado");
    foreach($dados as $v){
      $sql .= " (".$v['tipassoc_codigoid'].", '".$v['tipassoc_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela tipoassociado migrada </br />';

    return $this->contas();
  }

  public function contas() {
    /* PRECISA VER O QUE A TABELA DE CONTAS FAZ e caixa (no old)
    DB::statement('truncate table contas');

    //tipoconta
    DB::statement('truncate table tipoconta');
    $sql = "INSERT INTO tipoconta (id, counttype_nome) values ";
    $dados = $this->origem->query("SELECT * FROM tipoconta");
    foreach($dados as $v){
      $sql .= " (".$v['tipcont_codigoid'].", '".$v['tipcont_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela tipoconta migrada </br />';

    //contas
    $sql = "INSERT INTO contas (id, counttype_nome) values ";
    $dados = $this->origem->query("SELECT * FROM contas");
    foreach($dados as $v){
      $sql .= " (".$v['tipcont_codigoid'].", '".$v['tipcont_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela contas migrada </br />';

    */
    return $this->outros();
  }

  public function outros(){

    //convenio
    DB::statement('truncate table convenio');
    $sql = "INSERT INTO convenio (id, con_nome, tipconv_codigoid, con_referencia, con_prolabore) values ";
    $dados = $this->origem->query("SELECT * FROM convenio");
    foreach($dados as $v){
      $sql .= " (".$v['con_codigoid'].", '".$v['con_nome']."', '".$v['tipconv_codigoid']."', '".$v['con_referencia']."', '".$v['con_prolabore']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela convenio migrada </br />';
    
    //estatus NÃO POSSUI DADOS NA ORIGEM
    //exercicio NÃO POSSUI DADOS NA ORIGEM
    //saldo NÃO POSSUI DADOS NA ORIGEM


    //tipoconvenio
    DB::statement('truncate table tipoconvenio');
    $sql = "INSERT INTO tipoconvenio (id, tipconv_nome) values ";
    $dados = $this->origem->query("SELECT * FROM tipoconvenio");
    foreach($dados as $v){
      $sql .= " (".$v['tipconv_codigoid'].", '".$v['tipconv_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela tipoconvenio migrada </br />';

    return $this->associados();
  }

  public function associados() {
    // VERIFICAR CAMPOS QUE NÃO FORAM ENCONTRADOS
    //associado
    DB::statement('SET @@global.max_allowed_packet = 100000000');
    DB::statement('truncate table associado');
    $sql = "INSERT INTO associado (id, assoc_nome, assoc_matricula, assoc_cpf, assoc_rg, assoc_datanascimento, assoc_sexo, assoc_profissao, created_at, assoc_fone, assoc_email, assoc_cep, assoc_endereco, assoc_complemento, assoc_bairro, assoc_uf, assoc_cidade, assoc_observacao, tipassoc_codigoid, cla_codigoid, assoc_banco, assoc_agencia, assoc_conta, assoc_tipoconta, assoc_estadocivil, assoc_fone2, assoc_ativosn, assoc_dataativacao, assoc_datadesligamento, assoc_contrato, ag_codigoid, assoc_identificacao) values ";
    //assoc_removesn
    $dados = $this->origem->query("SELECT * FROM associado");
    foreach($dados as $v){
      $sql .= " (".$v['assoc_codigoid'].", '".$v['assoc_nome']."', '".$v['assoc_matricula']."', '".$v['assoc_cpf']."', '".$v['assoc_rg']."', '".$v['assoc_datanascimento']."', '".$v['assoc_sexo']."', '".$v['assoc_profissao']."', '".$v['assoc_dataassociado']."', '".$v['assoc_fone']."', '".$v['assoc_email']."', '".$v['assoc_cep']."', '".$v['assoc_endereco']."', '".$v['assoc_complemento']."', '".$v['assoc_bairro']."', '".$v['assoc_uf']."', '".$v['assoc_cidade']."', '".$v['assoc_observacao']."', '".$v['tipassoc_codigoid']."', '".$v['cla_codigoid']."', '".$v['assoc_banco']."', '".$v['assoc_agencia']."', '".$v['assoc_conta']."', '".$v['assoc_tipoconta']."', '".$v['assoc_estadocivil']."', '".$v['assoc_fone2']."', '".$v['assoc_ativosn']."', '".$v['assoc_dataativacao']."', '".$v['assoc_datadesligamento']."', '".$v['assoc_contrato']."', '".$v['ag_codigoid']."', '".$v['assoc_identificacao']."'),";
      //, '".$v['assoc_foto']."'
      //, '".$v['assoc_fone3']."'
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela associado migrada </br />';


  }
}
