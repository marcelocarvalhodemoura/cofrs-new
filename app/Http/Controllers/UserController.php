<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function index(){

        $userList = User::join('tipousuario', 'tipousuario.tipusr_codigoid', '=', 'usuario.tipusr_codigoid')->get();

        $userType = UserType::all();

        $data = [
            'category_name' => 'users',
            'page_name' => 'users',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'userList' => $userList,
            'userType' => $userType
        ];
        return view('user.list')->with($data);
    }

    public function store(Request $request)
    {
        try {
            User::updateOrCreate(
                ['usr_codigoid' => $request->post('userId')],
                [
                    'usr_nome'=>$request->post('name'),
                    'usr_usuario'=>$request->post('user'),
                    'usr_email'=>$request->post('email'),
                    'usr_senha' => Hash::make($request->post('password')),
                    'tipusr_codigoid' =>$request->post('usertype'),
                    'usr_removesn' => 0
                ]
            );

            return response()->json(['status'=> 'success', 'msg'=>'Usuário salvo com sucesso!']);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }
}
