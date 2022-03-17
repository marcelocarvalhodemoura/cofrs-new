<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use \Yajra\DataTables\DataTables;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class AccountTypeController
 * @package App\Http\Controllers
 */
class AccountTypeController extends Controller
{
  public function index(Request $request)
  {

    if (!Session::has('user')) {
      return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3,4])){
      return redirect()->route('dashboard');
    }

    if ($request->ajax()) {

      //load all AccountType
      $AccountTypeList = AccountType::orderBy('counttype_nome', 'ASC')->get();

      return DataTables::of($AccountTypeList)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="' . $row->id . '"/>';
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    $data = [
      'category_name' => 'AccountType',
      'page_name' => 'AccountType',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
    ];


    return view('AccountType.list')->with($data);
  }

  public function store(Request $request)
  {

    try {
      AccountType::updateOrCreate(
        ['id' => $request->post('id')],
        [
          'counttype_nome'  =>  $request->post('counttype_nome'),
        ]
      );

      return response()->json(['status' => 'success', 'msg' => 'Banco salvo com sucesso!']);
    } catch (Exception $e) {
      return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
    }
  }


  public function getItem($id)
  {
    return response()->json(AccountType::where('id', '=', $id)->get());
  }
}
