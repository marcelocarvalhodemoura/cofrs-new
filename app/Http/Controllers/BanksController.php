<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banks;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use \Yajra\DataTables\DataTables;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class BanksController
 * @package App\Http\Controllers
 */
class BanksController extends Controller
{
    public function index(Request $request)
    {

        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        if ($request->ajax()) {

            //load all banks
            $banksList = Banks::orderBy('name_bank', 'ASC')->get();

            return DataTables::of($banksList)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="' . $row->id . '"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'category_name' => 'banks',
            'page_name' => 'banks',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];


        return view('banks.list')->with($data);
    }

    public function store(Request $request)
    {

        try {
            Banks::updateOrCreate(
                ['id' => $request->post('id')],
                [
                    'name_bank'  =>  $request->post('name_bank'),
                    'bank_agency' => $request->post('bank_agency'),
                    'bank_account' => $request->post('bank_account'),
                ]
            );

            return response()->json(['status' => 'success', 'msg' => 'Banco salvo com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }


    public function getItem($id)
    {
        return response()->json(Banks::where('id', '=', $id)->get());
    }
}
