<?php

namespace App\Http\Controllers;

use App\Models\CategoryConvenants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use  \Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class CategoryConvenantController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        if(!in_array(Session::get('typeId'),[1,2,3])){
            return redirect()->route('dashboard');
        }

        if($request->ajax()){
            try {
                $listsCategoryCovenant = CategoryConvenants::orderBy('tipconv_nome', 'asc')->get();
                return DataTables::of($listsCategoryCovenant)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'.$row->tipconv_codigoid.'"/>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

        } else {
            Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o lista de categorias de convênios.');
        }

        $data = [
            'category_name' => 'covenants',
            'page_name' => 'covenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];



        return view('categoryconvenants.list')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if($request->ajax()){

            try {
                CategoryConvenants::updateOrCreate(
                    ['id' => $request->categoriesConvenantsId ],
                    [
                        'tipconv_nome' => $request->name,
                    ]
                );
                Log::channel('daily')->info('Usuário '.Session::get('user').' criou a categorias de convênio '.$request->post('name').'.');

                return response()->json(['status'=> 'success', 'msg'=>'Categoria salva com sucesso!']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesCovenants($id){
        try{
            return response()->json(CategoryConvenants::where('tipconv_codigoid', '=', $id)->get());
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }
    }
}
