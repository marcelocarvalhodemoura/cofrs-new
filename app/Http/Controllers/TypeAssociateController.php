<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typeassociate;

class TypeAssociateController extends Controller
{
    /**
     * @return mixed
     */
    public function list()
    {
        return Typeassociate::orderBy('tipassoc_nome', 'asc')->get();
    }
}
