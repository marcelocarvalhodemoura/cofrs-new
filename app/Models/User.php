<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $table = 'usuario';

    protected $primaryKey = 'id';
    protected $date = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usr_nome',
        'usr_usuario',
        'usr_email',
        'usr_senha',
        'tipusr_codigoid'
    ];
}
