<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'lancamento';

    /**
     * @var string
     */
    protected $primaryKey = 'lanc_codigoid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lanc_valortotal',
        'lanc_numerodaparcela',
        'lanc_datavencimento',
        'lanc_valorparcela',
        'con_codigoid',
        'assoc_codigoid',
        'lanc_contrato'
    ];
}
