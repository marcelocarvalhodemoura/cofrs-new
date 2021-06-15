<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Associate;

use App\Models\Competition;
use App\Models\Convenant;
use App\Models\Portion;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConvenantController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $associateList = Associate::all();
        $competitionList = Competition::all();
        $agreementList = Agreement::all();


        $data = [
            'category_name' => 'covenants',
            'page_name' => 'covenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        $lists = [
            'associateList'=> $associateList,
            'competitionList'=> $competitionList,
            'agreementList' => $agreementList,
        ];

        return view('covenants.list', $lists)->with($data);
    }

    public function getCovenants(Request $request)
    {
        if($request->ajax()){

            $dynamicWhere = [];

            if($request->post('selAssociate')){
                $dynamicWhere[] = ['associado.assoc_codigoid', $request->selAssociate];
            }

            if($request->post('selAgreement')){
                $dynamicWhere[] = ['convenio.con_codigoid', $request->selAgreement];
            }

            if($request->post('selCompetition')){
                $dynamicWhere[] = ['lanc.con_codigoid', $request->selCompetition];
            }

            if($request->post('selStatus')){
                $dynamicWhere[] = ['parcelamento.par_status', $request->selStatus];
            }

            $convenantList = Portion::join('lancamento', 'lancamento.lanc_codigoid', '=', 'parcelamento.lanc_codigoid')
                ->leftjoin('competencia', 'competencia.com_codigoid', '=', 'parcelamento.com_codigoid')
                ->leftjoin('associado', 'associado.assoc_codigoid', '=', 'lancamento.assoc_codigoid')
                ->leftjoin('convenio', 'convenio.con_codigoid', '=', 'lancamento.con_codigoid')
                ->where($dynamicWhere)
                ->get();

            return response()->json($convenantList);
        }

    }
}
