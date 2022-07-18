<?php

namespace App\Http\Controllers;

use App\Models\Depent;
use App\Models\Installment;
use Illuminate\Http\Request;
use App\Models\Associate;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use \Yajra\DataTables\DataTables;

use App\Models\Agreement;
use App\Models\Classification;


class ReportsController extends Controller
{
    /**
     * Class ReportsController
     * @param Request $request
     * @package App\Http\Controllers
     */

  public function associate(Request $request) {
    if (!Session::has('user')) {
        return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3])){
      return redirect()->route('dashboard');
    }

    $agreementList = Agreement::orderBy('con_nome', 'asc')->get();
    $classificationList = Classification::orderBy('cla_nome', 'asc')->get();
    $referenceList = Agreement::distinct()->orderBy('con_referencia', 'asc')->get('con_referencia');

    $data = [
      'category_name' => 'reports',
      'page_name' => 'reports',
      'has_scrollspy' => 0,
      'scrollspy_offset' => '',
      'alt_menu' => 0,
      'agreementList' => $agreementList,
      'classificationList' => $classificationList,
      'referenceList' => $referenceList,
    ];

    return view('reports.associate')->with($data);
  }

  public function agreement(Request $request) {
    if (!Session::has('user')) {
        return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3])){
      return redirect()->route('dashboard');
    }

    

  }

  public function covenant(Request $request) {
    if (!Session::has('user')) {
        return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3])){
      return redirect()->route('dashboard');
    }

    

  }

  public function cashflow(Request $request) {
    if (!Session::has('user')) {
        return redirect()->route('login');
    }
    if(!in_array(Session::get('typeId'),[1,2,3])){
      return redirect()->route('dashboard');
    }

    

  }

  public function aReport(Request $request) {
    dd($_POST);

    switch($request->post('typeReport')){
      case "associate":
        break;
      case "agreement":
        break;
      case "covenant":
        break;
      case "cashflow":
        break;
    }

    return DataTables::of($reportList)
      ->addIndexColumn()
      ->make(true);
  }




}