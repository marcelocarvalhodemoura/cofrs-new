<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
  use HasFactory;

  public $table = 'movimentacao';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id_conta',
    'id_estatus',
    'descricao',
    'data_vencimento',
    'credito',
    'valor',
  ];
}
