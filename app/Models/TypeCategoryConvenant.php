<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeCategoryConvenant extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'convenio';

    /**
     * @var string
     */
//    protected $primaryKey = 'tipassoc_codigoid';
    protected $date = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'con_nome',
        'tipconv_codigoid',
        'con_referencia',
        'con_prolabore',
    ];
}
