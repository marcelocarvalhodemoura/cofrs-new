<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;
    public $table = 'competencia';

    protected $fillable = [
        'com_datainicio',
        'com_datafinal',
        'com_nome'
    ];
}
