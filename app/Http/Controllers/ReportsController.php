<?php

namespace App\Http\Controllers;

use App\Models\Depent;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Models\Associate;
use App\Models\Agreement;
use App\Models\Classification;
use App\Models\Typeassociate;

class ReportsController extends Controller
{

  public function __construct() {
    if (!Session::has('user')) {
      return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3])){
      return redirect()->route('dashboard');
    }
  }
  /**
   * Class ReportsController
   * @param Request $request
   * @package App\Http\Controllers
   */

  public function associate(Request $request) {
    $agreementList = Agreement::orderBy('con_nome', 'asc')->get();
    $classificationList = Classification::orderBy('cla_nome', 'asc')->get();
    $referenceList = Agreement::distinct()->orderBy('con_referencia', 'asc')->get('con_referencia');

    $data = [
      'category_name' => 'reports',
      'page_name' => 'reports',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'agreementList' => $agreementList,
      'classificationList' => $classificationList,
      'referenceList' => $referenceList,
    ];

    Log::channel('daily')->info('Usuário '.Session::get('user').' acessou os filtros do Relatório Por Associado.');

    return view('reports.associate')->with($data);
  }

  public function allAssociate(Request $request) {
    $sql = "SELECT DISTINCT(assoc_cidade), COUNT(id) AS cont FROM associado GROUP BY assoc_cidade ORDER BY assoc_cidade";
    $cidades = \DB::select($sql);
    $sql = "SELECT DISTINCT(assoc_uf), COUNT(id) AS cont FROM associado GROUP BY assoc_uf ORDER BY assoc_uf";
    $estados = \DB::select($sql);
    $tipo = Typeassociate::orderBy('tipassoc_nome', 'asc')->get();
    $classificationList = Classification::orderBy('cla_nome', 'asc')->get();


    $data = [
      'category_name' => 'reports',
      'page_name' => 'reports',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'estados' => $estados,
      'cidades' => $cidades,
      'tipo' => $tipo,
      'classificationList' => $classificationList,
    ];

    Log::channel('daily')->info('Usuário '.Session::get('user').' acessou os filtros do Relatório Todos os Associado.');

    return view('reports.allAssociate')->with($data);
 }

  public function agreement(Request $request) {
    $agreementList = Agreement::orderBy('con_nome', 'asc')->get();

    $data = [
      'category_name' => 'reports',
      'page_name' => 'reports',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'agreementList' => $agreementList,
    ];

    Log::channel('daily')->info('Usuário '.Session::get('user').' acessou os filtros do Relatório Conveniados.');

    return view('reports.agreement')->with($data);
  }

  public function covenant(Request $request) {

    $agreementList = Agreement::orderBy('con_nome', 'asc')->get();
    $classificationList = Classification::orderBy('cla_nome', 'asc')->get();
    $referenceList = Agreement::distinct()->orderBy('con_referencia', 'asc')->get('con_referencia');

    $data = [
      'category_name' => 'reports',
      'page_name' => 'reports',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'agreementList' => $agreementList,
      'classificationList' => $classificationList,
      'referenceList' => $referenceList,
    ];

    Log::channel('daily')->info('Usuário '.Session::get('user').' acessou os filtros do Relatório Convênios.');

    return view('reports.covenants')->with($data);

  }

  public function cashflow(Request $request) {

  }

  public function aReport(Request $request) {

    if($request->post('periodo')){
      $pp = explode(' a ',$request->post('periodo'));
      $inicio = implode('-',array_reverse(explode('/',$pp[0])));
      $fim = implode('-',array_reverse(explode('/',$pp[1])));
      }

    $retorno = [];

    switch($request->post('typeReport')){
      case "associate":
        $cab1 = \DB::table('associado')->select('assoc_nome','assoc_matricula')->where("assoc_cpf", "=", $request->post('cpf'))->first();

        $retorno['cabecalho'] = "Associado: ".$cab1->assoc_nome."<br/>
          CPF: ".$request->post('cpf')."<br />
          Matrícula: ".$cab1->assoc_matricula."<br />
          Período: ".$request->post('periodo');

        $sqlBusca = "SELECT
                cv.con_nome,
                cl.cla_nome,
                p.par_vencimentoparcela,
                p.par_numero,
                p.par_equivalente,
                l.lanc_numerodeparcela,
                l.lanc_contrato,
                p.par_valor,
                p.par_status
              FROM
                associado a,
                lancamento l,
                convenio cv,
                classificacao cl,
                parcelamento p
              WHERE
                a.assoc_cpf = '".$request->post('cpf')."'
                AND a.id = l.assoc_codigoid
                AND p.par_vencimentoparcela >= '".$inicio."'
                AND p.par_vencimentoparcela <= '".$fim."'
                AND l.con_codigoid = cv.id
                AND a.cla_codigoid = cl.id
                AND l.id = p.lanc_codigoid";

        if($request->post('convenio') != ''){
          $sqlBusca .= "AND cv.id = ".$request->post('convenio');
        }

        if($request->post('classificacao') != ''){
          $sqlBusca .= "AND cl.id = ".$request->post('classificacao');
        }

        if($request->post('referencia') != ''){
          $sqlBusca .= "AND cv.con_referencia = ".$request->post('convenio');
        }

        if($request->post('assoc_ativosn') != ''){
          $sqlBusca .= "AND a.assoc_ativosn = ".$request->post('assoc_ativosn');
        }

        $busca = \DB::select($sqlBusca);

        if($busca){
          foreach($busca as $b){
            $retorno['tabela'][] = array(
              'convenio' => $b->con_nome,
              'classificacao' => $b->cla_nome,
              'vencimento' => $b->par_vencimentoparcela,
              'parcela' => $b->par_numero,
              'equivalencia' => $b->par_equivalente,
              'quantidade' => $b->lanc_numerodeparcela,
              'contrato' => $b->lanc_contrato,
              'valor' => number_format($b->par_valor,2),
              'status' => $b->par_status,
            );
          }
        } else {
          $retorno['erro'] = "Não existem resultados para esta busca";
        }

        Log::channel('daily')->info('Usuário '.Session::get('user').' emitiu um relatório de Associados.');
        break;
      case "allAssociate":
  
          if($request->post('assoc_ativosn') == 1){
            $assoc_ativosn = "Ativos";
          }elseif($request->post('assoc_ativosn') == 2){
            $assoc_ativosn = "Inativos";
          }else{
            $assoc_ativosn = "Todos";            
          }

          if($request->post('classificacao') != ""){
            $classificationList = Classification::where('id', $request->post('classificacao'))->first();
            $classificacao = $classificationList->cla_nome;
          } else {
            $classificacao = "Todos";
          }

          if($request->post('referencia') != ""){
            $classificationList = Typeassociate::where('id', $request->post('referencia'))->first();
            $referencia = $classificationList->tipassoc_nome;
          } else {
            $referencia = "Todos";
          }

          if($request->post('uf') != ""){
            $uf = $request->post('uf');
          } else {
            $uf = "Todos";
          }

          if($request->post('cidade') != ""){
            $cidade = $request->post('cidade');
          } else {
            $cidade = "Todos";
          }

          $retorno['cabecalho'] = "UF: ".$uf."<br />
            Cidade: ".$cidade."<br />
            Status do cadastro: ".$assoc_ativosn."<br />
            Classificação: ".$classificacao."<br />
            Tipo: ".$referencia."<br />
            ";
  
          $sqlBusca = "SELECT
                          a.assoc_nome,
                          a.assoc_cpf,
                          a.assoc_datanascimento,
                          a.assoc_uf,
                          a.assoc_cidade,
                          a.assoc_cep,
                          CONCAT(a.assoc_endereco, ' ', a.assoc_complemento) AS endereco,
                          a.assoc_bairro,
                          IF(a.assoc_ativosn = 1, 'Ativo','Inativo') AS ativo,
                          t.tipassoc_nome,
                          c.cla_nome
                        FROM
                          associado a,
                          tipoassociado t,
                          classificacao c
                        WHERE
                          a.tipassoc_codigoid = t.id
                          AND c.id = a.cla_codigoid";
  
          if($request->post('uf') != ""){
            $sqlBusca .= " AND a.assoc_uf = '".$request->post('uf')."'";
          }

          if($request->post('cidade') != ""){
            $sqlBusca .= " AND a.assoc_cidade = '".$request->post('cidade')."'";
          }
  
          if($request->post('assoc_ativosn') != ''){
            $sqlBusca .= " AND a.assoc_ativosn = ".$request->post('assoc_ativosn');
          }
  
          if($request->post('classificacao') != ''){
            $sqlBusca .= " AND c.id = ".$request->post('classificacao');
          }
  
          if($request->post('referencia') != ''){
            $sqlBusca .= " AND t.id = ".$request->post('referencia');
          }

          $busca = \DB::select($sqlBusca);
  
          if($busca){
            foreach($busca as $b){
              $retorno['tabela'][] = array(
                'assoc_nome' => $b->assoc_nome,
                'assoc_cpf' => $b->assoc_cpf,
                'assoc_datanascimento' => $b->assoc_datanascimento,
                'assoc_uf' => $b->assoc_uf,
                'assoc_cidade' => $b->assoc_cidade,
                'assoc_cep' => $b->assoc_cep,
                'endereco' => $b->endereco,
                'assoc_bairro' => $b->assoc_bairro,
                'ativo' => $b->ativo,
                'tipassoc_nome' => $b->tipassoc_nome,
                'cla_nome' => $b->cla_nome,
              );
            }
          } else {
            $retorno['erro'] = "Não existem resultados para esta busca";
          }
  
          Log::channel('daily')->info('Usuário '.Session::get('user').' emitiu um relatório de Todos os Associados.');
          break;        
      case "agreement":
        $cab1 = \DB::table('convenio')->select('con_nome', 'con_referencia', 'con_prolabore')->where("id", "=", $request->post('convenio'))->first();

        $retorno['cabecalho'] = "Convênio: ".$cab1->con_nome."<br />
          Referência: ".$cab1->con_referencia."<br />
          Pró-labore: ".$cab1->con_prolabore."%<br />
          Período: ".$request->post('periodo');

          $sqlBusca = "SELECT
                        e.est_nome,
                        (SELECT SUM(p.par_valor) FROM lancamento l, parcelamento p, associado a
                          WHERE l.con_codigoid = c.id AND l.id = p.lanc_codigoid
                            AND p.par_vencimentoparcela >= '".$inicio."'
                            AND p.par_vencimentoparcela <= '".$fim."'
                            AND l.assoc_codigoid = a.id
                            AND p.deleted_at IS NULL
                            AND p.par_status = e.est_nome ) AS valor
                      FROM
                        estatus e,
                        convenio c
                      WHERE
                        c.id = ".$request->post('convenio');


        //echo $sqlBusca;
        $busca = \DB::select($sqlBusca);
        //dd($busca);
        if($busca){
          foreach($busca as $b){
            $retorno['tabela'][] = array(
              'st_pagamento' => $b->est_nome,
              'valor' => number_format($b->valor,2,',','.'),
            );
          }
        } else {
          $retorno['erro'] = "Não existem resultados para esta busca";
        }
        Log::channel('daily')->info('Usuário '.Session::get('user').' emitiu um relatório de Convênios.');
        break;
      case "covenant":
        $retorno['cabecalho'] = "Status de Pagamento";

        $sqlBusca = "SELECT
          a.assoc_nome,
          a.assoc_cpf,
          cv.con_nome,
          a.assoc_identificacao,
          p.par_vencimentoparcela,
          p.par_numero,
          p.par_equivalente,
          l.lanc_numerodeparcela,
          l.lanc_contrato,
          p.par_valor,
          p.par_status
        FROM
          associado a,
          lancamento l,
          convenio cv,
          classificacao cl,
          parcelamento p
        WHERE
          a.id = l.assoc_codigoid
          AND p.par_vencimentoparcela >= '".$inicio."'
          AND p.par_vencimentoparcela <= '".$fim."'
          AND p.deleted_at IS NULL
          AND l.con_codigoid = cv.id
          AND a.cla_codigoid = cl.id
          AND l.id = p.lanc_codigoid";

        if($request->post('convenio') != ''){
          $sqlBusca .= " AND cv.id = ".$request->post('convenio');
        }

        if($request->post('classificacao') != ''){
          $sqlBusca .= " AND cl.id = ".$request->post('classificacao');
        }

        if($request->post('referencia') != ''){
          $sqlBusca .= " AND cv.con_referencia = '".$request->post('referencia')."'";
        }

        if($request->post('status') != ''){
          $sqlBusca .= " AND p.par_status = '".$request->post('status')."'";
        }

        if($request->post('assoc_ativosn') != ''){
          $sqlBusca .= " AND a.assoc_ativosn = ".$request->post('assoc_ativosn');
        }

        if($request->post('cpf') != ''){
          $sqlBusca .= " AND a.assoc_cpf = '".$request->post('cpf')."'";
        }
        //dd($sqlBusca);

        $busca = \DB::select($sqlBusca);
        if($busca){
          foreach($busca as $b){
          $retorno['tabela'][] = array(
            'nome' => $b->assoc_nome,
            'cpf' => $b->assoc_cpf,
            'convenio' => $b->con_nome,
            'matricula' => $b->assoc_identificacao,
            'vencimento' => $b->par_vencimentoparcela,
            'vencimentoFormatado' => implode('/',array_reverse(explode('-',$b->par_vencimentoparcela))),
            'contrato' => $b->lanc_contrato,
            'valor' => number_format($b->par_valor,2),
            'status' => $b->par_status,
            'par_numero' => $b->par_numero,
            'lanc_numerodeparcela' => $b->lanc_numerodeparcela,
            );
          }
        } else {
          $retorno['erro'] = "Não existem resultados para esta busca";
        }

        Log::channel('daily')->info('Usuário '.Session::get('user').' emitiu um relatório de Convêniados.');
        break;
      case "cashflow":
        break;
    }

    return json_encode($retorno);
  }

}
