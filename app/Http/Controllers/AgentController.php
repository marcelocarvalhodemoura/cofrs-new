<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function list()
    {
        return Agent::orderBy('ag_nome', 'ASC')->get();
    }
}
