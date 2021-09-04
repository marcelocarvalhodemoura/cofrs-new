<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryConvenants extends Model
{
    use HasFactory;

    public $table = 'tipoconvenio';

    protected $fillable = [
        'tipconv_nome'
    ];
}
