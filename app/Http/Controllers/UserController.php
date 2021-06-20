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


class UserController extends Controller
{

    public function index(Request $request){

        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        //load all UserTypes
        $userType = UserType::all();

        if($request->ajax()){

            //load all users and usertypes
            $userList = User::join('tipousuario', 'tipousuario.tipusr_codigoid', '=', 'usuario.tipusr_codigoid')->get();

            return DataTables::of($userList)
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logOut(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }


    public function authentication(Request $request)
    {
        // Load information by usernam from User table
        $userModel = User::join('tipousuario', 'tipousuario.tipusr_codigoid', '=', 'usuario.tipusr_codigoid')
            ->where('usr_usuario', '=', $request->username)
            ->get();

        // Verify that model is empty
        if($userModel == '[]'){
            return response()->json([
                'status' => 'error',
                'msg' => 'Usuário inválido'
            ]);
        }

        // Check that password isn't a HASH
        if(!Hash::check($request->password, $userModel[0]->usr_senha)){
            return response()->json([
                'status' => 'error',
                'msg' => 'Senha inválida'
            ]);
        }

        //Create any sessions
        Session::put([
            'user'=> $userModel[0]->usr_usuario,
            'name'=> $userModel[0]->usr_nome,
            'type'=> $userModel[0]->tipusr_nome,
        ]);


        return response()->json([
            'status' => 'success',
            'msg' => ''
        ]);
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
                    'usr_nome'  =>  $request->post('name'),
                    'usr_usuario' => $request->post('user'),
                    'usr_email' => $request->post('email'),
                    'usr_senha' => Hash::make($request->post('password1')),
                    'tipusr_codigoid' => $request->post('usertype'),
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
