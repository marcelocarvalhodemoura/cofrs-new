<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
  use HasFactory;

  public $table = 'alerts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'date',
    'titulo',
    'texto',
    'autor',
    'tipo',
  ];
}
