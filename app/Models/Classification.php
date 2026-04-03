<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'classificacao';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $date = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cla_nome'
    ];
}
