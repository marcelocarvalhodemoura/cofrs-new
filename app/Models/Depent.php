<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depent extends Model
{
    use HasFactory;

    public $table = 'dependentes';

    protected $primaryKey = 'dep_codigoid';
    protected $date = ['deleted_at'];

    protected $fillable = [
        'dep_nome',
        'dep_fone',
        'dep_rg',
        'dep_cpf',
        'assoc_codigoid'
    ];
}
