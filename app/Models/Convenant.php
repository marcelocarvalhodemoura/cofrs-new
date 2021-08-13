<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convenant extends Model
{
    use HasFactory;

    public $table = 'lancamento';

    protected $fillable = [
        'id',
        'lanc_valortotal',
        'lanc_numeroparcela',
        'lanc_datavencimento',
        'lanc_valorparcela',
        'con_codigoid',
        'assoc_codigoid',
        'lanc_contrato',
        'est_codigoid',
    ];
}
