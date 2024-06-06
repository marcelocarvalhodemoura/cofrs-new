<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertUserView extends Model
{
  use HasFactory;

  public $table = 'alerts_user_view';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id_alert',
    'id_user',
    'date',
  ];
}
