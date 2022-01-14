<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
  use HasFactory;

  public $table = 'contas';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id_banco',
    'id_tipoconta',
    'agencia',
    'conta',
  ];
}
