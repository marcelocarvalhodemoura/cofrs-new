<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'parcelamento';

    protected $fillable = [
        'par_numero',
        'par_valor',
        'lanc_codigoid',
        'par_vencimentoparcela',
        'par_observacao',
        'par_status',
        'com_codigoid',
        'par_equivalente',
        'par_habilitasn',
        'deleted_at'
    ];
}
