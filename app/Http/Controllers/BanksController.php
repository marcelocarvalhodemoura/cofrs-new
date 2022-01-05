<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class BanksController
 * @package App\Http\Controllers
 */
class BanksController extends Controller
{
    public function list()
    {
        return Agent::orderBy('ag_nome', 'ASC')->get();
    }
}
