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
      "host" => "187.45.196.168",
      "database" => "cofrs_velho",
      "username" => "cofrs_velho",
      "password" => "e2q2d5n6@"
  ]);
    $this->origem = DB::connection('cofrs_old')->getPdo();
  }

  public function index(){

    ini_set('memory_limit', "2048M");
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    ini_set('max_execution_time', '-1');


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
    //contapagar NÃO POSSUI DADOS NA ORIGEM
    //contasreceber NÃO POSSUI DADOS NA ORIGEM

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
    $sql = "INSERT INTO associado (id, assoc_nome, assoc_matricula, assoc_cpf, assoc_rg, assoc_datanascimento, assoc_sexo, assoc_profissao, created_at, assoc_fone, assoc_email, assoc_cep, assoc_endereco, assoc_complemento, assoc_bairro, assoc_uf, assoc_cidade, assoc_observacao, tipassoc_codigoid, cla_codigoid, assoc_banco, assoc_agencia, assoc_conta, assoc_tipoconta, assoc_estadocivil, assoc_fone2, assoc_ativosn, assoc_dataativacao, assoc_datadesligamento, assoc_contrato, ag_codigoid, assoc_identificacao, deleted_at) values ";
    //assoc_removesn
    $dados = $this->origem->query("SELECT * FROM associado");
    foreach($dados as $v){

      $sql .= " (".$v['assoc_codigoid'].", '".$v['assoc_nome']."', '".$v['assoc_identificacao']."', '".$v['assoc_cpf']."', '".$v['assoc_rg']."', '".$v['assoc_datanascimento']."', '".$v['assoc_sexo']."', '".$v['assoc_profissao']."', '".$v['assoc_dataassociado']."', '".$v['assoc_fone']."', '".$v['assoc_email']."', '".$v['assoc_cep']."', '".$v['assoc_endereco']."', '".$v['assoc_complemento']."', '".$v['assoc_bairro']."', '".$v['assoc_uf']."', '".$v['assoc_cidade']."', '".$v['assoc_observacao']."', '".$v['tipassoc_codigoid']."', '".$v['cla_codigoid']."', '".$v['assoc_banco']."', '".$v['assoc_agencia']."', '".$v['assoc_conta']."', '".$v['assoc_tipoconta']."', '".$v['assoc_estadocivil']."', '".$v['assoc_fone2']."', '".$v['assoc_ativosn']."', '".$v['assoc_dataativacao']."', '".$v['assoc_datadesligamento']."', '".$v['assoc_contrato']."', '".$v['ag_codigoid']."', '".$v['assoc_matricula']."',  ";

      if($v['assoc_ativosn'] == 1){
        $sql .= "NULL";
      } else {
        $sql .= "'".date('Y-m-d H:i:s')."'";
      }

      $sql .= "),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela associado migrada </br />';


    //dependentes NÃO POSSUI DADOS NA ORIGEM

    return $this->competencias();
  }

  public function competencias(){
    //competencia
    DB::statement('truncate table competencia');
    $sql = "INSERT INTO competencia (id, com_datainicio, com_datafinal, com_nome) values ";
    $dados = $this->origem->query("SELECT * FROM competencia");
    foreach($dados as $v){
      $sql .= " (".$v['com_codigoid'].", '".$v['com_datainicio']."', '".$v['com_datafinal']."', '".$v['com_nome']."'),";
    }
    DB::statement(substr($sql,0,-1));
    echo 'Tabela competencia migrada </br />';

    //lancamento
    DB::statement('truncate table lancamento');
    $sql = "INSERT INTO lancamento (id, lanc_valortotal, lanc_numerodeparcela, lanc_datavencimento, con_codigoid, assoc_codigoid, lanc_contrato) values ";
    $dados = $this->origem->query("SELECT * FROM lancamento");
    foreach($dados as $v){
      $sql .= " (".$v['lanc_codigoid'].", '".$v['lanc_valortotal']."', '".$v['lanc_numerodeparcela']."', '".$v['lanc_datavencimento']."', '".$v['con_codigoid']."', '".$v['assoc_codigoid']."', '".$v['lanc_contrato']."'),";
    }
    //, '".$v['lanc_valorparcela']."'
    DB::statement(substr($sql,0,-1));
    echo 'Tabela lancamento migrada </br />';

    //parcelamento
    DB::statement('truncate table parcelamento');
    $sql = "INSERT INTO parcelamento (id, par_numero, par_valor, lanc_codigoid, par_vencimentoparcela, par_observacao, par_status, com_codigoid, par_equivalente, par_habilitasn, deleted_at) values ";
    $dados = $this->origem->query("SELECT * FROM parcelamento");
    foreach($dados as $v){

      $sql .= " (".$v['par_codigoid'].", '".$v['par_numero']."', '".$v['par_valor']."', '".$v['lanc_codigoid']."', '".$v['par_vencimentoparcela']."', '".$v['par_observacao']."', '".$v['par_status']."', '".$v['com_codigoid']."', '".$v['par_equivalente']."', '".$v['par_habilitasn']."', ";

      if($v['par_habilitasn'] == 1){
        $sql .= "NULL";
      } else {
        $sql .= "'".date('Y-m-d H:i:s')."'";
      }

      $sql .= "),";
    }
    //, '".$v['lanc_valorparcela']."'
    DB::statement(substr($sql,0,-1));
    echo 'Tabela parcelamento migrada </br />';

    DB::statement('SET FOREIGN_KEY_CHECKS = 1');

  }
}
