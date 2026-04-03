<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Session;

class Associate extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public $table = 'associado';
    protected $primaryKey = 'id';
    public $incrementing = true;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Rastreia todos os campos fillable
            ->logOnlyDirty() // Salva apenas se algo mudou
            ->dontSubmitEmptyLogs() // impede logs se nada mudou
            ->useLogName('associados') // Categoria exclusiva para este Model
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'Cadastrou o associado',
                    'updated' => 'Alterou os dados do associado',
                    'deleted' => 'Removeu o associado',
                    'restored' => 'Restaurou o associado',
                    default   => "Realizou a ação: {$eventName}",
                };
            })
            ;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_type = \App\Models\User::class;
        $activity->causer_id = Session::get('id');

        if ($eventName === 'updated' || $eventName === 'created') {
            $props = $activity->properties->toArray();
            $labels = self::logLabels(); // Pega o seu array de legendas
            $meanings = self::fieldMeanings();

            // Função para traduzir as chaves de um array (attributes ou old)
            $traduzirArray = function($array) use ($labels, $meanings) {
                $novoArray = [];
                foreach ($array as $campo => $valor) {
                    if (isset($meanings[$campo]) && array_key_exists((string)$valor, $meanings[$campo])) {
                        \Log::info("Campo: $campo - Valor: $valor");
                        $valor = $meanings[$campo][(string)$valor];
                    }
                    // Se existir uma legenda no logLabels, usa ela. Senão, mantém o nome original.
                    $novaChave = $labels[$campo] ?? $campo;
                    $novoArray[$novaChave] = $valor;
                }
                return $novoArray;
            };

            // Traduz o array de novos valores
            if (isset($props['attributes'])) {
                $props['attributes'] = $traduzirArray($props['attributes']);
            }

            // Traduz o array de valores antigos
            if (isset($props['old'])) {
                $props['old'] = $traduzirArray($props['old']);
            }
            
            if (isset($props['attributes']) && isset($props['attributes']['tipassoc_codigoid'])) {
                $tipo = \App\Models\Typeassociate::find($props['attributes']['tipassoc_codigoid']);
                $props['attributes']['Tipo de Associado'] = $tipo->tipassoc_nome ?? 'N/A';
                unset($props['attributes']['tipassoc_codigoid']);
            }
            if (isset($props['old']) && isset($props['old']['tipassoc_codigoid'])) {
                $tipoAntigo = \App\Models\Typeassociate::find($props['old']['tipassoc_codigoid']);
                $props['old']['Tipo de Associado'] = $tipoAntigo->tipassoc_nome ?? 'N/A';
                unset($props['old']['tipassoc_codigoid']);
            }
            
            if (isset($props['attributes']) && isset($props['attributes']['cla_codigoid'])) {
                $tipo = \App\Models\Classification::find($props['attributes']['cla_codigoid']);
                $props['attributes']['Classificação'] = $tipo->cla_nome ?? 'N/A';
                unset($props['attributes']['cla_codigoid']);
            }
            if (isset($props['old']) && isset($props['old']['cla_codigoid'])) {
                $tipoAntigo = \App\Models\Classification::find($props['old']['cla_codigoid']);
                $props['old']['Classificação'] = $tipoAntigo->cla_nome ?? 'N/A';
                unset($props['old']['cla_codigoid']);
            }
            
            if (isset($props['attributes']) && isset($props['attributes']['ag_codigoid'])) {
                $tipo = \App\Models\Agent::find($props['attributes']['ag_codigoid']);
                $props['attributes']['Agente'] = $tipo->ag_nome ?? 'N/A';
                unset($props['attributes']['ag_codigoid']);
            }
            if (isset($props['old']) && isset($props['old']['ag_codigoid'])) {
                $tipoAntigo = \App\Models\Agent::find($props['old']['ag_codigoid']);
                $props['old']['Agente'] = $tipoAntigo->ag_nome ?? 'N/A';
                 unset($props['old']['ag_codigoid']);
           }
            
            $activity->properties = collect($props);
        }
    }

    public static function logLabels(): array
    {
        return [
            'assoc_nome'               => 'Nome',
            'assoc_matricula'          => 'Matrícula',
            'assoc_cpf'                => 'CPF',
            'assoc_rg'                 => 'RG',
            'assoc_datanascimento'     => 'Data de Nascimento',
            'assoc_sexo'               => 'Gênero/Sexo',
            'assoc_profissao'          => 'Profissão',
            'assoc_dataassociado'      => 'Data de Associação',
            'assoc_fone'               => 'Telefone Principal',
            'assoc_email'              => 'E-mail',
            'assoc_cep'                => 'CEP',
            'assoc_endereco'           => 'Endereço',
            'assoc_complemento'        => 'Complemento',
            'assoc_bairro'             => 'Bairro',
            'assoc_uf'                 => 'Estado (UF)',
            'assoc_cidade'             => 'Cidade',
            'assoc_observacao'         => 'Observações',
            'tipassoc_codigoid'        => 'Tipo de Associado (ID)', //AQUI
            'cla_codigoid'             => 'Classificação (ID)', //AQUI
            'assoc_banco'              => 'Banco',
            'assoc_agencia'            => 'Agência Bancária',
            'assoc_conta'              => 'Número da Conta',
            'assoc_tipoconta'          => 'Tipo de Conta',
            'assoc_estadocivil'        => 'Estado Civil',
            'assoc_fone2'              => 'Telefone Secundário',
            'assoc_ativosn'            => 'Status Ativo (S/N)', //AQUI
            'assoc_dataativacao'       => 'Data de Ativação',
            'assoc_datadesligamento'   => 'Data de Desligamento',
            'assoc_contrato'           => 'Número do Contrato',
            'assoc_contrato_terceiros' => 'Contrato de Terceiros',
            'ag_codigoid'              => 'Agente (ID)', //AQUI
            'assoc_removesn'           => 'Removido (S/N)', //AQUI
            'assoc_identificacao'      => 'Identificação',
        ];
    }

    public static function fieldMeanings(): array
    {
        return [
            'assoc_ativosn' => [
                1 => 'Ativo',
                2 => 'Desativado',
            ],
        ];
    }
}
