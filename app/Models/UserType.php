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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipusr_nome'
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
