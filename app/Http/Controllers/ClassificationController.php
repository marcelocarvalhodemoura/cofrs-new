<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

/**
 * Class ClassificationController
 * @package App\Http\Controllers
 */
class ClassificationController extends Controller
{
    /**
     * @return mixed
     */
    public function list()
    {
        return Classification::orderBy('cla_nome', 'ASC')->get();
    }
}
