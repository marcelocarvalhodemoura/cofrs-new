<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Associate;
use App\Models\Classification;
use App\Models\Convenant;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConvenantController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }


        $convenantList = Convenant::join('associado', 'associado.assoc_codigoid', '=', 'lancamento.assoc_codigoid')
            ->leftJoin('convenio', 'convenio.con_codigoid', '=', 'lancamento.con_codigoid')
            ->leftJoin('estatus', 'estatus.est_codigoid', '=', 'lancamento.est_codigoid')
        ->get();

        $associateList = Associate::all();
        $classificationList = Classification::all();
        $agreementList = Agreement::all();
        $statusList = Status::all();

        $data = [
            'category_name' => 'covenants',
            'page_name' => 'covenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        $lists = [
            'associateList'=> $associateList,
            'classificationList'=> $classificationList,
            'agreementList' => $agreementList,
            'statusList' => $statusList
        ];

        return view('covenants.list', $lists)->with($data);
    }

    public function getAssociates()
    {
        return response()->json(Associate::all());
    }
}
