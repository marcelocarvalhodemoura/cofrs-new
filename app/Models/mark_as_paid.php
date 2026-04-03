<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark_as_paid extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'baixa_arquivo';

    protected $fillable = [
        'id',
        'extensionArchive',
        'competencia',
        'convenio',
        'processado',
        'tempo',
        'usuario_id',
    ];
}
