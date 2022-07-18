<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associate extends Model
{
    use HasFactory;

    public $table = 'associado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assoc_nome',
        'assoc_matricula',
        'assoc_cpf',
        'assoc_rg',
        'assoc_datanascimento',
        'assoc_sexo',
        'assoc_profissao',
        'assoc_dataassociado',
        'assoc_fone',
        'assoc_email',
        'assoc_cep',
        'assoc_endereco',
        'assoc_complemento',
        'assoc_bairro',
        'assoc_uf',
        'assoc_cidade',
        'assoc_observacao',
        'tipassoc_codigoid',
        'cla_codigoid',
        'assoc_banco',
        'assoc_agencia',
        'assoc_conta',
        'assoc_tipoconta',
        'assoc_estadocivil',
        'assoc_fone2',
        'assoc_ativosn',
        'assoc_dataativacao',
        'assoc_datadesligamento',
        'assoc_contrato',
        'assoc_contrato_terceiros',
        'ag_codigoid',
        'assoc_removesn',
        'assoc_identificacao',
    ];
}
