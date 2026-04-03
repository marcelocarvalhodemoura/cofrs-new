<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark_as_paid_line extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    public $table = 'baixa_arquivo_linhas';

    protected $fillable = [
        'id',
        'id_baixa_arquivo',
        'ln',
        'contrato',
        'valorRejeitado',
        'motivoRejeicaoDireita',
        'motivoRejeicaoEsquerda',
        'matricula',
        'referencia',
        'mensagemMelhorada',
    ];
}
