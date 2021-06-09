<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    public $table = 'convenio';

    protected $fillable = [
        'con_nome',
        'tipconv_codigoid',
        'con_referencia',
        'con_prolabore'
    ];
}
