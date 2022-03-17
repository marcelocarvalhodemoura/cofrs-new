<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\AccountType;
use App\Models\Banks;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\DataTables;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class BankAccountController
 * @package App\Http\Controllers
 */
class BankAccountController extends Controller
{
  public function index(Request $request)
  {

    if (!Session::has('user')) {
      return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3,4])){
      return redirect()->route('dashboard');
    }

    $Banks = Banks::all();
    $AccountType = AccountType::all();


    if ($request->ajax()) {

      //load all contas
      $contasList = BankAccount::select('contas.id', DB::raw("CONCAT(contas.agencia,'/',contas.conta) as count"), 'b.febraban_code', 'b.name_bank', 'tc.counttype_nome')
        ->leftJoin('banks as b', 'b.id', '=', 'contas.id_banco')
        ->leftJoin('tipoconta as tc', 'tc.id', '=', 'contas.id_tipoconta')
        ->orderBy('b.name_bank', 'asc')
        ->orderBy('tc.counttype_nome', 'asc')
        ->get();

      //dd($contasList);

      return DataTables::of($contasList)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="' . $row->id . '"/>';
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    $data = [
      'category_name' => 'contas',
      'page_name' => 'contas',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'Banks' => $Banks,
      'AccountType' => $AccountType,
    ];


    return view('BankAccount.list')->with($data);
  }

  public function store(Request $request)
  {

    try {
      BankAccount::updateOrCreate(
        ['id' => $request->post('id')],
        [
          'id_banco'  =>  $request->post('id_banco'),
          'id_tipoconta' => $request->post('id_tipoconta'),
          'agencia' => $request->post('agencia'),
          'conta' => $request->post('conta'),
        ]
      );

      return response()->json(['status' => 'success', 'msg' => 'Conta salva com sucesso!']);
    } catch (Exception $e) {
      return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
    }
  }


  public function getItem($id)
  {
    return response()->json(BankAccount::where('id', '=', $id)->get());
  }
}
