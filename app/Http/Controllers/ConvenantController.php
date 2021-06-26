<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Associate;

use App\Models\Competence;
use App\Models\Convenant;
use App\Models\Portion;

use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class ConvenantController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $associateList = Associate::all();
        $competitionList = Competence::all();
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

            $convenantList = Portion::join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                ->leftjoin('competencia', 'competencia.com_codigoid', '=', 'parcelamento.com_codigoid')
                ->leftjoin('associado', 'associado.assoc_codigoid', '=', 'lancamento.assoc_codigoid')
                ->leftjoin('convenio', 'convenio.con_codigoid', '=', 'lancamento.con_codigoid')
                ->where($dynamicWhere)
                ->get();

            return response()->json($convenantList);
        }

    }

    public function store(Request $request)
    {


        $convenantModel = new Convenant();

        try {
            //create Convenants
            $convenantModel->lanc_valortotal = str_replace(',','.', $request->total);
            $convenantModel->lanc_numerodeparcela = $request->number;
            $convenantModel->con_codigoid = $request->convenants;
            $convenantModel->lanc_datavencimento = date('Y-m-d', strtotime($request->duedate));;
            $convenantModel->assoc_codigoid = $request->associate;
            $convenantModel->est_codigoid = 2;

            $convenantModel->save();
            $lasInsertIdConvenat = Convenant::latest('id')->first();


            try {

                $currentMonth = explode("-", date('Y-m-d'));

                $monthUpdated = intval($currentMonth[1]);
                $yearUpdated  = intval($currentMonth[0]);

                //create portion
                for ($i = 1; $request->number >= $i; $i++) {

                    $monthUpdated++;

                    if ($monthUpdated > 12) {
                        $monthUpdated = 01;
                        $yearUpdated++;
                    }

                    if ($monthUpdated < 10) {
                        $monthUpdated = "0".$monthUpdated;
                    }

                    $competenceID = Competence::where('com_nome', '=', $monthUpdated.'/'.$yearUpdated)->get();

                    if((int)$competenceID[0]['com_codigoid'] > 0 ){
                        $portionModel = new Portion();

                        $portionModel->par_numero = $i;
                        $portionModel->par_valor = str_replace(',','.',$request->portion);
                        $portionModel->lanc_codigoid = $lasInsertIdConvenat->id;
                        $portionModel->par_vencimentoparcela = $yearUpdated.'-'.$monthUpdated.'-10';
                        $portionModel->par_observacao = '';
                        $portionModel->par_status = 'Pendente';
                        $portionModel->com_codigoid = $competenceID[0]['com_codigoid'];
                        $portionModel->par_equivalente = $i;
                        $portionModel->par_habilitasn = 0;

                        $portionModel->save();


                    }


                }

            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

            return response()->json(['status'=>'success', 'msg'=> 'Formulário salvo com sucesso']);

        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }





    }
}
