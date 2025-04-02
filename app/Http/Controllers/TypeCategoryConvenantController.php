<?php

namespace App\Http\Controllers;

use App\Models\TypeCategoryConvenant;
use App\Models\CategoryConvenants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use \Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class TypeCategoryConvenantController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        if(!in_array(Session::get('typeId'),[1,2,3])){
            return redirect()->route('dashboard');
        }



        if($request->ajax()){

            //load all users and usertypes
            $typeCategoryList = TypeCategoryConvenant::select('*', 'convenio.id as conv_codigoid')
                ->join('tipoconvenio', 'tipoconvenio.id', '=', 'convenio.tipconv_codigoid')
                ->get();

            return DataTables::of($typeCategoryList)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'.$row->conv_codigoid.'"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o lista de tipos de convênio.');
        }

        $categoryConvenantsData = CategoryConvenants::all();

        $data = [
            'category_name' => 'typecategoryconvenants',
            'page_name' => 'typecategoryconvenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'categoryConvenants' => $categoryConvenantsData
        ];


        return view('typecategoryconvenants.list')->with($data);

    }

    public function store(Request $request)
    {
        //die(str_replace(',','.',$request->post('con_comissao_cofrs')));
        try {
            TypeCategoryConvenant::updateOrCreate(
                ['id' => $request->post('typeCategoryId')],
                [
                    'con_nome'  =>  $request->post('name'),
                    'tipconv_codigoid' => $request->post('typeCategory'),
                    'con_referencia' => $request->post('reference'),
                    'con_despesa_canal' => str_replace(',','.',$request->post('con_despesa_canal')),
                    'con_comissao_cofrs' => str_replace(',','.',$request->post('con_comissao_cofrs')),
                ]
            );

            Log::channel('daily')->info('Usuário '.Session::get('user').' adicionou o tipos de convênio '.$request->name.'.');

            return response()->json(['status'=> 'success', 'msg'=>'Formulário salvo com sucesso!']);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    public function getAgreement($id)
    {
        try{
            return response()->json(TypeCategoryConvenant::find($id));
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }


}
