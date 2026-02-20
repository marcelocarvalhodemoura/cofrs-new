<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeassociate extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'tipoassociado';

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
        'tipassoc_nome'
    ];
}
