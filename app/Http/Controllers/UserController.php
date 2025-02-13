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
use App\Helpers;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\MailController;

class UserController extends Controller
{

    
    public function teste(){
        $contact = new MailController([
            'name' => 'John',
            'email' => 'jonatascrizel@gmail.com',
            'message' => 'Olá mundo',
            'subject' => 'teste',
        ]);
        $contact->sendEmail();
    }

    public function index(Request $request)
    {

        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        if(!in_array(Session::get('typeId'),[1,2])){
            return redirect()->route('dashboard');
        }


        //load all UserTypes
        $userType = UserType::all();

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
        } else {
            Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o lista de usuários do sistema.');
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

    public function profile(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $userModel = User::find($request->userId);

        Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o cadastro do usuário '.$userModel->user.'.');

        $data = [
            'category_name' => 'users',
            'page_name' => 'users',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'userType' => UserType::all()
        ];

        return view('user.profile')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logOut(Request $request)
    {
        Log::channel('daily')->info('Usuário '.Session::get('user').' se deslogou.');

        $request->session()->flush();

        return redirect()->route('login');
    }


    public function authentication(Request $request)
    {
        // Load information by usernam from User table
        $userModel = User::select('*', 'usuario.id AS user_id')->join('tipousuario', 'tipousuario.id', '=', 'usuario.tipusr_codigoid')
            ->where('usr_usuario', '=', $request->username)
            ->get();

        // Verify that model is empty
        if ($userModel == '[]') {
            return response()->json([
                'status' => 'error',
                'msg' => 'Usuário inválido'
            ]);
        }

        // Check that password isn't a HASH
        if (!Hash::check($request->password, $userModel[0]->usr_senha)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Senha inválida'
            ]);
        }

        //Create any sessions
        Session::put([
            'id'  => $userModel[0]->user_id,
            'user' => $userModel[0]->usr_usuario,
            'name' => $userModel[0]->usr_nome,
            'type' => $userModel[0]->tipusr_nome,
            'typeId' => $userModel[0]->tipusr_codigoid,
        ]);

        Log::channel('daily')->info('Usuário '.Session::get('user').' logou no sistema.');

        return response()->json([
            'status' => 'success',
            'msg' => '',
            'typeId' => $userModel[0]->tipusr_codigoid,
        ]);
    }

    public function getUser($id)
    {
        $userModel = User::where('id', '=', $id)->get();
        Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o cadastro do usuário '.$userModel[0]->usr_usuario);
        return response()->json($userModel);
    }

    public function store(Request $request)
    {
        $userModel = new User();

        try {

            $userModel->usr_nome = $request->post('name');
            $userModel->usr_usuario = $request->post('user');
            $userModel->usr_email = $request->post('email');
            $userModel->usr_senha = Hash::make($request->post('password1'));
            $userModel->tipusr_codigoid = $request->post('usertype');

            $userModel->save();

            Log::channel('daily')->info('Usuário '.Session::get('user').' criou o cadastro do usuário '.$request->post('user').'.');

            return response()->json(['status' => 'success', 'msg' => 'Usuário salvo com sucesso!']);
        } catch (Exception $e) {
            Log::channel('daily')->error('Usuário '.Session::get('user').' tentou criar o cadastro do usuário '.$request->post('user').' e obteve o erro: '.$e->getMessage().'.');
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {

        $userModel = User::find($request->userId);

        try {

            $userModel->usr_nome = $request->post('name');
            $userModel->usr_usuario = $request->post('user');
            $userModel->usr_email = $request->post('email');
            $userModel->tipusr_codigoid = $request->post('usertype');
            $userModel->save();

            Log::channel('daily')->info('Usuário '.Session::get('user').' alterou o cadastro do usuário '.$request->post('user').'.');

            return response()->json(['status' => 'success', 'msg' => 'Usuário salvo com sucesso!']);

        } catch (Exception $e) {
            Log::channel('daily')->error('Usuário '.Session::get('user').' tentou alterar o cadastro do usuário '.$request->post('user').' e obteve o erro: '.$e->getMessage().'.');
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }


    /**
     * Forgot password
     */
    public function forgotPassword(Request $request)
    {
        if ($request->ajax()) {

            if ($request->post('editPassword') != $request->post('editPassword2')) {
                return response()->json(['status' => 'error', 'msg' => 'Senha e Conf. de Senha devem ser iguais!']);
            }

            try {

                $userModel = User::find($request->post('editUserID'));

                $userModel->usr_senha = Hash::make($request->post('editPassword'));

                $userModel->save();

                Log::channel('daily')->info('Usuário '.Session::get('user').' alterou a senha do usuário '.$userModel->usr_usuario.'.');

                return response()->json(['status' => 'success', 'msg' => 'Usuário atualizado com sucesso!']);
            } catch (Exception $e) {
                Log::channel('daily')->error('Usuário '.Session::get('user').' tentou alterar a senha do usuário '.$request->usr_usuario.' e obteve o erro: '.$e->getMessage().'.');

                return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
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
        $userModel = User::find($id);
        try {
            $msg = 'Usuário '.Session::get('user').' deletou o usuário '.$userModel->usr_usuario.'.';
            User::find($id)->delete();
            Log::channel('daily')->info($msg);
            return true;
        } catch (Exception $e) {
            Log::channel('daily')->error('Usuário '.Session::get('user').' tentou deletar o usuário '.$userModel->usr_usuario).' e obteve o erro: '.$e->getMessage().'.';
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

}
