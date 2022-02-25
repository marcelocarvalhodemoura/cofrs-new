<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Cashflow;
use App\Models\Associate;
use DateTime;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $vigencia = date('m/Y');

        $resumo_operacoes = Cashflow::select('credito', DB::raw("SUM(valor) as valor"))
        ->where(DB::raw("YEAR(data_vencimento)"), '=', date('Y'))
        ->where(DB::raw("MONTH(data_vencimento)"), '=', date('m'))
        ->groupBy('credito')
        ->get();

        $op_cresc = Cashflow::select('credito',
            DB::raw("SUM(valor) as valor"),
            DB::raw("YEAR(data_vencimento) as ano"),
            DB::raw("MONTH(data_vencimento) as mes"))
        ->where(DB::raw("YEAR(data_vencimento)"), '<=', date('Y'))
        ->where(DB::raw("MONTH(data_vencimento)"), '<=', date('m'))
        ->groupBy('credito')
        ->groupBy(DB::raw("YEAR(data_vencimento)"))
        ->groupBy(DB::raw("MONTH(data_vencimento)"))
        ->orderbyDesc('data_vencimento')
        ->limit(12)
        ->get();

        $crescimento_operacoes = [];
        //seto um ano antes para garantir que o gráfico terá os 11 meses mesmo que não exista movimentação
        $dt = new DateTime('first day of -11 months');
        for ($i = 0; $i < 12; $i++) {
            $m = $dt->format('m');
            // preciso fazer isso porque o método retorna uma string
            settype($m,'integer');

            $crescimento_operacoes[0][$m.'/'.$dt->format('Y')] = 0;
            $crescimento_operacoes[1][$m.'/'.$dt->format('Y')] = 0;
            $dt->modify('+1 month');
        }
        //preencho o array com os valores que tiver
        if($op_cresc){
            foreach($op_cresc as $item){
                $crescimento_operacoes[$item->credito][$item->mes.'/'.$item->ano] = $item->valor;
            }
        }

        $associados = Associate::select(DB::raw("COUNT(associado.id) AS quantidade"),DB::raw("COUNT(lancamento.id) AS conveniados"))
        ->leftJoin('lancamento','assoc_codigoid','=','associado.id')
        ->where('assoc_ativosn','=',1)
        ->first();

        $ass_total = $associados->quantidade;
        $ass_nconveniados = $associados->quantidade - $associados->conveniados;
        $ass_conveniados = $associados->conveniados;

        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'analytics',
            'has_scrollspy' => 0,
            'offsetTop' => '',
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'vigencia' => $vigencia,
            'resumo_operacoes' => $resumo_operacoes,
            'ass_total' => ($ass_total ? $ass_total : 1),
            'ass_nconveniados' => $ass_nconveniados,
            'ass_conveniados' => $ass_conveniados,
            'crescimento_operacoes' => $crescimento_operacoes,
        ];

        return view('dashboard')->with($data);
    }
}
