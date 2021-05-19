<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Associate;
use DataTables;

class AssociateController extends Controller
{
    public function index(Request $request)
    {


        if($request->ajax()){

            //load all associates
            $associateList = Associate::all();

            return \Yajra\DataTables\DataTables::of($associateList)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'.$row->assoc_codigoid.'"/>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'category_name' => 'associate',
            'page_name' => 'associate',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];


        return view('associate.list')->with($data);
    }
}
