<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public $table = 'usuario';
   // use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usr_nome',
        'usr_usuario',
        'usr_senha',
        'usr_email',
        'tipusr_codigoid',
        'usr_ativosn',
        'usr_removesn'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
      //  'password',
        //'remember_token',
    //];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
   // protected $casts = [
     //   'email_verified_at' => 'datetime',
    //];
}
