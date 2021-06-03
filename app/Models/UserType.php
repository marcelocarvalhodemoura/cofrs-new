<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserType extends Model
{
    public $table = 'tipousuario';
    // use HasFactory, Notifiable;

    protected $primaryKey = 'tipusr_codigoid';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipusr_nome'
    ];


}
