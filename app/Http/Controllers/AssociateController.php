<?php

namespace App\Http\Controllers;

use App\Models\Depent;
use App\Models\Installment;
use Illuminate\Http\Request;
use App\Models\Associate;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use  \Yajra\DataTables\DataTables;

class AssociateController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        if($request->ajax()){

            //load all associates
            $associateList = Associate::join('tipoassociado', 'associado.tipassoc_codigoid', '=', 'tipoassociado.tipassoc_codigoid')->get();

            return DataTables::of($associateList)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'.$row->assoc_codigoid.'"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'category_name' => 'associate',
            'page_name' => 'associate',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];


        return view('associate.list')->with($data);
    }

    public function getDependents($id)
    {
        return response()->json(Depent::where('assoc_codigoid', '=', $id)->get());
    }

    public function storeDependets(Request $request)
    {

        try {

            if($request->ajax()){

                $dependentsForm = $request->post();

                $dependentsModel = new Depent();

                for ($i = 0; count($dependentsForm["depName"]) > $i; $i++){

                    $dependentsModel->dep_nome =  $dependentsForm["depName"][$i];
                    $dependentsModel->dep_rg =  $dependentsForm["depIdentify"][$i];
                    $dependentsModel->dep_cpf =  $dependentsForm["depRegistration"][$i];
                    $dependentsModel->dep_fone =  $dependentsForm["depPhone"][$i];
                    $dependentsModel->assoc_codigoid =  $dependentsForm["assocID"];

                    $dependentsModel->save();
                }

            }

            return response()->json(['status'=> 'success', 'msg'=>'Dependentes salvos com sucesso!']);

        }catch (Exception $e){

            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);

        }
    }

    public function deleteDependents($id)
    {
        try {

            return Depent::find($id)->delete();

        }catch (Exception $exception){

            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if($request->ajax()){
            $datePtToMysql = implode('-', array_reverse(explode('/', $request->post('born'))));
        }

        try {
            Associate::updateOrCreate(
                ['assoc_codigoid' => $request->post('associateId')],
                [
                    'assoc_nome'  =>  $request->post('name'),
                    'assoc_matricula' => $request->post('registration'),
                    'assoc_cpf' => $request->post('cpf'),
                    'assoc_rg' => $request->post('rg'),
                    'assoc_datanascimento' => $datePtToMysql,
                    'assoc_sexo' => $request->post('sexo'),
                    'assoc_profissao' => $request->post('job'),
                    'assoc_fone' => $request->post('phone'),
                    'assoc_email' => $request->post('email'),
                    'assoc_cep' => $request->post('cep'),
                    'assoc_cidade' => $request->post('city'),
                    'assoc_endereco' => $request->post('adress'),
                    'assoc_complemento' => $request->post('complement'),
                    'assoc_bairro' => $request->post('district'),
                    'assoc_uf' => $request->post('state'),
                    'assoc_observacao' => $request->post('description'),
                    'tipassoc_codigoid' => $request->post('typeassociate'),
                    'cla_codigoid' => $request->post('classification'),
                    'assoc_banco' => $request->post('bank'),
                    'assoc_agencia' => $request->post('bank_agency'),
                    'assoc_conta' => $request->post('count'),
                    'assoc_tipoconta' => $request->post('typeassociate'),
                    'assoc_estadocivil' => $request->post('civilstatus'),
                    'assoc_fone2' => $request->post('phone2'),
                    'assoc_ativosn' => 1,
                    'assoc_contrato' => $request->post('contract'),
                    'ag_codigoid' => $request->post('typeagent'),
                    'assoc_removesn' => 0,
                    'assoc_identificacao' => $request->post('identify')
                ]
            );

            return response()->json(['status'=> 'success', 'msg'=>'Associado salvo com sucesso!']);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    public function getAssociate($id)
    {
        try{
            return response()->json(Associate::find($id));
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    public function delete($id)
    {
        try{

            return Associate::find($id)->delete();

        }catch (Exception $e){

            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);

        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public function associateConvenants($id)
    {
        //load all Portion
        return $portions = Installment::join('convenio', 'convenio.con_codigoid', '=', 'lancamento.con_codigoid')
            ->join('parcelamento', 'parcelamento.lanc_codigoid', '=', 'lancamento.lanc_codigoid')
            ->where('assoc_codigoid', '=', $id)
            ->where('par_status', '=', 'Pendente')
            ->get();
    }

}
