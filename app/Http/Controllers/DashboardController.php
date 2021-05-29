<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'analytics',
            'has_scrollspy' => 0,
            'offsetTop' => '',
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        return view('dashboard')->with($data);
    }
}
