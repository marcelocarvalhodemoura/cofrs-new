<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Mockery\Exception;

class UserController extends Controller
{

    public function index(Request $request){
        //load all UserTypes
        $userType = UserType::all();

        if($request->ajax()){

            //load all users and usertypes
            $userList = User::latest()
                ->join('tipousuario', 'tipousuario.tipusr_codigoid', '=', 'usuario.tipusr_codigoid')
                ->get();

            return \Yajra\DataTables\DataTables::of($userList)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'.$row->usr_codigoid.'"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'category_name' => 'users',
            'page_name' => 'users',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'userType' => $userType
        ];


        return view('user.list')->with($data);
    }

    public function getUser($id)
    {
        return response()->json(User::find($id));
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

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request)
    {
        if($request->ajax()){
            if($request->post('editPassword') != $request->post('editPassword2')){
                return response()->json(['status'=>'error', 'msg'=>'Senha e Conf. de Senha devem ser iguais!']);
            }

            try {

                $userModel = User::find($request->post('editUserID'));

                $userModel->usr_senha = Hash::make($request->post('editPassword'));

                $userModel->save();

                return response()->json(['status'=> 'success', 'msg'=>'Usuário atualizado com sucesso!']);

            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    protected function delete($id)
    {
        try {
            User::find($id)->delete();
            return true;
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }
    }
}
