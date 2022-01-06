<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
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

            //load all users and usertypes
            $userList = User::select('*', 'usuario.id AS user_id')
                ->join('tipousuario', 'tipousuario.id', '=', 'usuario.tipusr_codigoid')
                ->get();

            return DataTables::of($userList)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="' . $row->user_id . '"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        
}
