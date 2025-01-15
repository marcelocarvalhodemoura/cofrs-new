<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\AlertUser;
use App\Models\AlertUserView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use \Yajra\DataTables\DataTables;
use App\Helpers;
use Illuminate\Support\Facades\Log;


class AlertController extends Controller
{
    public function index(Request $request){
      if (!Session::has('user')) {
        return redirect()->route('login');
      }

      if ($request->ajax()) {
        $alertList = DB::select(DB::raw("SELECT a.id, DATE_FORMAT(a.date, '%d/%m/%Y %T') as date, a.titulo, a.autor, aut.tipo, aut.id as tipo_id
                                        FROM alerts a
                                        LEFT JOIN alerts_tipo aut ON a.tipo = aut.id
                                        ORDER BY a.date DESC"));
      return DataTables::of($alertList)
          ->addIndexColumn()
          ->addColumn('action', function ($row) {
              $btn = '<button type="button" class="btn btn-outline-dark btn-sm mb-2 mr-2 rounded-rounded bs-popover" onclick="verAlerta(' . $row->id . ', 1)" data-container="body" data-trigger="hover" data-bs-placement="top" data-content="Ver alerta" data-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
              <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-2 rounded-rounded bs-popover" onclick="quemViu(' . $row->id . ')" data-container="body" data-trigger="hover" data-bs-placement="top" data-content="Quem leu o alerta" data-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg></button>
              ';
              if($row->tipo_id == 2){
                $btn .= '<button type="button" class="btn btn-primary  bs-popover rounded mb-2 mr-2" data-toggle="modal" data-placement="top" data-container="body" data-trigger="hover" data-content="Alterar" onclick="editAlerta(' . $row->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></button> ';
              }
              return $btn;
          })
          ->rawColumns(['action'])
          ->make(true);
      } else {
          Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o lista de alertas do sistema.');
      }

    $lists = [
      'userList' => User::orderBy('usr_nome','asc')->get(),
    ];

    $data = [
      'category_name' => 'alertas',
      'page_name' => 'alertas',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
    ];

    return view('alert.list', $lists)->with($data);
    }

    public function meusAlertas(Request $request){
      if (!Session::has('user')) {
        return redirect()->route('login');
      }

      if ($request->ajax()) {
        $alertList = DB::select(DB::raw("SELECT a.id, DATE_FORMAT(a.date, '%d/%m/%Y %T') as date, a.titulo, a.autor, aut.tipo, DATE_FORMAT((SELECT auv.date FROM alerts_user_view auv WHERE auv.id_alert = a.id AND auv.id_user = au.id_user), '%d/%m/%Y %T') AS visto
                                        FROM alerts a
                                        LEFT JOIN alerts_user au ON au.id_alert = a.id
                                        LEFT JOIN alerts_tipo aut ON a.tipo = aut.id 
                                        WHERE au.id_user = " . Session::get('id') . "
                                        ORDER BY a.date DESC"));
//dd($alertList);
        return DataTables::of($alertList)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-outline-dark btn-sm mb-2 mr-2 rounded-rounded bs-popover" onclick="verAlerta(' . $row->id . ')" data-container="body" data-trigger="hover" data-bs-placement="top" data-content="Ver alerta" data-original-title="" title=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
      } else {
            Log::channel('daily')->info('Usuário '.Session::get('user').' acessou o lista de alertas pessoais.');
        }

    $data = [
      'category_name' => 'meus-alertas',
      'page_name' => 'meus-alertas',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
    ];

    return view('alert.my')->with($data);
    }

    public function aAlerts(){
      $alertas =  DB::select(DB::raw("SELECT a.*
                            FROM alerts a
                            LEFT JOIN alerts_user au ON au.id_alert = a.id
                            WHERE au.id_user = " . Session::get('id') . "
                            AND (SELECT id FROM alerts_user_view auv WHERE auv.id_alert = a.id AND auv.id_user = au.id_user) IS NULL
                            ORDER BY a.date DESC"));
      
      return response()->json($alertas);                    
    }

    public function aVerAlerta(Request $request){
      $alerta =  DB::select(DB::raw("SELECT a.*, (SELECT id_user FROM alerts_user_view WHERE id_alert = a.id AND id_user = " . Session::get('id') . ") AS visto FROM alerts a WHERE a.id = " . $request->id));

      return response()->json($alerta[0]);
    }

    public function aVisualizado(Request $request){
      AlertUserView::insert(['id_alert' => $request->id_alerta, 'id_user' => Session::get('id')]);
    }

    public function aUsersAlert(Request $request){
      $users = AlertUser::where('id_alert', $request->id_alert)->get();
      return response()->json($users);
    }

    public function aQuemViu(Request $request){
      $usuarios =  DB::select(DB::raw('SELECT
                                        u.usr_nome AS nome,
                                        (SELECT auv.date FROM alerts_user_view auv WHERE auv.id_user = u.id AND au.id_alert = auv.id_alert) AS lido
                                      FROM
                                        alerts_user au,
                                        usuario u  
                                      WHERE
                                        au.id_user = u.id
                                        AND au.id_alert =  ' . $request->id_alert .'
                                      ORDER BY
                                      u.usr_nome'));
      
      return response()->json($usuarios); 
    }                   

    public function store(Request $request){
      if($request->post('id')){
        $msg = "editou";
      } else {
        $msg = "adicionou";
      }


      try {
        $insert = Alert::updateOrCreate(
            ['id' => $request->post('id')],
            [
                'titulo'  =>  $request->post('titulo'),
                'texto' => nl2br($request->post('texto')),
                'data' => date('Y-m-d H:m:i'),
                'autor' => Session::get('name'),
                'tipo' => 2,
            ]
        );
        //echo $insert->id;
        AlertUser::where('id_alert', $insert->id)->delete();
        AlertUserView::where('id_alert', $insert->id)->delete();

        foreach ($request->post('users') as $key => $value) {
            AlertUser::insert(['id_alert' => $insert->id, 'id_user' => $value]);
        }

        Log::channel('daily')->info('Usuário '.Session::get('user').' '.$msg.' o alerta '.$request->post('titulo').'.');
        
        return response()->json(['status' => 'success', 'msg' => 'Salvo com sucesso!']);

      } catch (Exception $e) {
        Log::channel('daily')->info('Usuário '.Session::get('user').' tentou '.$msg.' o alerta '.$request->post('titulo').' e obteve o erro:'.$e->getMessage().'.');
        return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
      }
  }

}
