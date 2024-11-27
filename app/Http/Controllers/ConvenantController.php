<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Agreement;
use App\Models\Associate;
use App\Models\Agent;

use App\Models\Competence;
use App\Models\Convenant;
use App\Models\Portion;
use App\Models\Status;

use App\Models\Typeassociate;
use App\Models\Classification;

use App\Models\TypeCategoryConvenant;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use SimpleXLSX;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\JsonResponse;
use File;
use App\Helpers;


use DateTime;
use DateInterval;

/**
 *
 */
class ConvenantController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }


        $associateList = Associate::orderBy('assoc_nome','asc')->get();
        $competitionList = Competence::orderBy('com_datainicio','desc')->get();
        $agreementList = Agreement::orderBy('con_nome', 'asc')->get();
        $statusList = Status::orderBy('est_nome', 'asc')->get();

        $currentCompetence = date('m/Y');


        $data = [
            'category_name' => 'covenants',
            'page_name' => 'covenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
            'currentCompetence' => $currentCompetence,
        ];

        $lists = [
            'associateList'=> $associateList,
            'competitionList'=> $competitionList,
            'agreementList' => $agreementList,
            'statusList' => $statusList,
        ];

        return view('covenants.list', $lists)->with($data);
    }

    public function createFile(Request $request)
    {
        Storage::disk('local')->delete('example.txt');

        if($request->company[0] === "Ipe"){

            //Build Title File
            $titleFile = str_pad('H000000000000000CCDRPP677', 39, " ", STR_PAD_RIGHT);
            $complementTitle = str_pad($request->monthCompetence, 67,"0", STR_PAD_RIGHT );
            //Header file
            $contentFile = $titleFile . $request->yearCompetence . $complementTitle . "\r\n";

            //Verify how reference checked
            foreach($request->convenants as  $typeConvenant) {
                // Validate Diversos reference
                if($typeConvenant === "DIVERSOS"){
                    // Return Sql Diversos
                    $convenantDiversoAgroup = self::typeReferenceAgrouped($request->monthCompetence . '/' . $request->yearCompetence, 'DIVERSOS');

                    //List Diversos
                    foreach ($convenantDiversoAgroup as $convenantDiverso){

                        $contract = str_pad($convenantDiverso->lanc_contrato, 40, " ", STR_PAD_RIGHT);
                        $reference = str_pad($convenantDiverso->con_referencia, 20, " ", STR_PAD_RIGHT);

                        //Format money to 2 decimal
                        $diversosTotal = number_format($convenantDiverso->valor_total_diversos, 2, '.', '');
                        $diversosTotal = explode('.', $diversosTotal);

                        $valuePortion = str_pad($diversosTotal[0].$diversosTotal[1], 9 ,  "0", STR_PAD_LEFT);

//                        $bigestDate = explode("-", $convenantDiverso->datamaior);

                        $contentFile .= "D" . str_pad(trim($convenantDiverso->assoc_identificacao), 12, "0", STR_PAD_LEFT). $reference . $contract .$request->yearCompetence.$request->monthCompetence.$valuePortion."0000000000000000000000\r\n";
                    }
                }

                // Validate Monthly Payment
                if($typeConvenant === "MENSALIDADE"){
                    //Return Sql Monthly Payment
                    $convenantMonthlyPaymentAgroup = self::typeReferenceAgrouped($request->monthCompetence . '/' . $request->yearCompetence, 'MENSALIDADE');
                    //List Monthly Payment
                    foreach($convenantMonthlyPaymentAgroup as $convenantMonthlyPayment){

                        $contractMonthPay = str_pad($convenantMonthlyPayment->assoc_contrato, 40, " ", STR_PAD_RIGHT);
                        $reference = str_pad($convenantMonthlyPayment->con_referencia, 20, " ", STR_PAD_RIGHT);
                        //Format money to 2 decimal
                        $monthlyPaymentTotal = number_format($convenantMonthlyPayment->par_valor, 2, '.', '');
                        $monthlyPaymentTotal = explode('.', $monthlyPaymentTotal);

                        $valuePortionMonthlyPayment = str_pad($monthlyPaymentTotal[0].$monthlyPaymentTotal[1], 9 ,  "0", STR_PAD_LEFT);

                        $contentFile .= "D".str_pad(trim($convenantMonthlyPayment->assoc_identificacao), 12, "0", STR_PAD_LEFT).$reference.$contractMonthPay.'000000'.$valuePortionMonthlyPayment."0000000000000000000000\r\n";

                    }//end to Foreach Monthly Payment
                }

                if($typeConvenant === 'EMPRESTIMO'){
                    $loanConvenant = self::typeReferenceAgrouped($request->monthCompetence . '/' . $request->yearCompetence, 'EMPRESTIMO');

                    //List Monthly Payment
                    foreach($loanConvenant as $loan){
                        $explodeDate = explode('-', $loan->lanc_datavencimento);
                        $day = str_pad($explodeDate[2], 2, "0", STR_PAD_LEFT);
                        $month = str_pad($explodeDate[1], 2, "0", STR_PAD_LEFT);

                        $contractMonthPay = str_pad($loan->lanc_contrato, 40, " ", STR_PAD_RIGHT);
                        $reference = str_pad($loan->con_referencia, 20, " ", STR_PAD_RIGHT);
                        //Format money to 2 decimal
                        $monthlyPaymentTotal = number_format($loan->valor_total_emprestimo, 2, '.', '');
                        $monthlyPaymentTotal = explode('.', $monthlyPaymentTotal);

                        $valuePortionMonthlyPayment = str_pad($monthlyPaymentTotal[0].$monthlyPaymentTotal[1], 9 ,  "0", STR_PAD_LEFT);

                        $contentFile .= "D".str_pad(trim($loan->assoc_identificacao), 12, "0", STR_PAD_LEFT).$reference.$contractMonthPay.$explodeDate[0].$month.$valuePortionMonthlyPayment."0000000000000000000000\r\n";

                    }//end to Foreach Monthly Payment
                }


            }

        } else {
            echo "tesouro";
        }

        Storage::disk('local')->put('example.txt', $contentFile);

        return Storage::disk('local')->get('example.txt');

    }

    private function typeReferenceAgrouped($competenceName, $reference)
    {

        switch ($reference){
            case 'MENSALIDADE':
                $referenceSql = Portion::select('assoc_identificacao', 'par_valor', 'con_referencia', 'assoc_contrato')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->where('cla_codigoid', '=', 15)
                    ->where('par_numero', '=', 1)
                    ->whereNull('parcelamento.deleted_at')
                    ->orderBy('assoc_matricula', 'asc')
                    ->get();
                break;

            case 'DIVERSOS':
                $referenceSql = Portion::select('assoc_identificacao', 'con_referencia', 'lanc_contrato')
                    ->selectRaw('SUM(par_valor) as valor_total_diversos')
                    //->selectRaw('MAX(lancamento.lanc_datavencimento) as datamaior')

                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')

                    ->where('cla_codigoid', '=', 15)
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->whereNull('parcelamento.deleted_at')
                    ->whereNull('lancamento.deleted_at')

                    ->groupBy('assoc_identificacao')
                    ->orderBy('assoc_identificacao', 'asc')
                    ->get();
                break;
            case 'EMPRESTIMO':
                $referenceSql = Portion::select('assoc_identificacao', 'con_referencia', 'lanc_contrato', 'lanc_datavencimento')
                    ->selectRaw('SUM(par_valor) as valor_total_emprestimo')
                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->where('par_habilitasn', '=', 1)
                    ->where('cla_codigoid', '=', 15)
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->whereNull('parcelamento.deleted_at')
                    ->whereNull('lancamento.deleted_at')
                    ->groupBy('lancamento.lanc_contrato')
                    ->get();
                break;
        }

        return $referenceSql;
    }


    public function uploadFile(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getCovenants(Request $request)
    {

        if($request->ajax()){

            $dynamicWhere = [];
            $dynamicWherePortion = [];

            if($request->post('selAssociate')){
                $dynamicWhere[] = ['lancamento.assoc_codigoid', '=', $request->selAssociate];
            }

            if($request->post('selAgreement')){
                $dynamicWhere[] = ['lancamento.con_codigoid','=', $request->selAgreement];
            }

            if ($request->post('selCompetence')) {
                $dynamicWherePortion[] = ['competencia.id', '=', $request->selCompetence];
            }

            if ($request->post('selStatus')) {
                $dynamicWherePortion[] = ['par_status', '=', $request->selStatus];
            }

            if ($dynamicWhere === []){
                return response()->json(['status'=>'error', 'msg'=> 'Selecione um filtro para pesquisar!']);
            }

            try {

                //load Convenants from table lancamentos
                $convenantList = Convenant::select(
                    'assoc_nome',
                    'assoc_cpf',
                    'con_nome',
                    'con_referencia',
                    'con_codigoid',
                    'assoc_contrato',
                    'lanc_contrato',
                    'lanc_datavencimento',
                    'lanc_numerodeparcela',
                    'lanc_valortotal',
                    'con_referencia',
                    'lancamento.id AS lanc_codigoid'
                    )
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->where($dynamicWhere)
                    ->get();

                foreach ($convenantList as $index => $item) {
                    //load portion within lanc_codigoid iqual to id from lancamento
                    $convenantList[$index]['portion'] = Portion::select(
                        'com_nome',
                        'par_numero',
                        'par_valor',
                        'par_status',
                        'par_vencimentoparcela',
                        'par_equivalente',
                        'par_observacao',
                        'parcelamento.id AS par_codigoid'
                        )
                        ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                        ->where('parcelamento.lanc_codigoid', $item->lanc_codigoid)
                        ->where('parcelamento.deleted_at',null)
                        ->where($dynamicWherePortion)
                        ->orderBy('com_datafinal','asc')
                        ->get();
                }

                return response()->json($convenantList);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }
        }

    }

    /**
     * @param $id
     * @param string $status
     * @return mixed
     */
    protected function changeStatusPortion($id, $status)
    {
        try{
            // load portion
            $portion = Portion::where('id', $id)
                ->update(
                    [
                        'par_status' => $status
                    ]
                );

            return $portion;
        } catch (Exception $e) {
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function changePayment(Request $request)
    {

        foreach ($request->id as $id){
            try{

                self::changeStatusPortion($id, 'Pago');

            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }
        }

        return response()->json(['status'=>'success', 'msg'=> 'Parcela Quitada com sucesso!']);

    }

    /**
     * @param $compentence
     * @return mixed
     */
    protected function verifyCompetent($compentence)
    {
        $competenceList = Competence::where('com_nome','=', $compentence)->get();

        if(isEmpty($competenceList)){

            $newData = explode('/', $compentence);

            $beginMouth = $newData[0] - 1;
            $beginYear = $newData[1];

            if($beginMouth === 0 ){
                $beginMouth = 12;
                $beginYear = $beginYear - 1;
            }

            try {

                Competence::firstOrCreate([
                    'com_nome' => $compentence,
                    'com_datainicio' => $beginYear.'-'. $beginMouth .'-11',
                    'com_datafinal' => $newData[1].'-'.$newData[0].'-10',
                ]);


                return Competence::where('com_nome','=', $compentence)->get();
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

        }


    }


    public function getMonthlyPayment()
    {
        $dataConvenants = Convenant::where('con_codigoid', '=', 31)->count();

        $data = [
            'data' => $dataConvenants,
        ];

        return response()->json($data,'200');
    }

    /**
     * Process xlsx to add associate
     * @param Request $request
     * @return JsonResponse
     */
    public static function associateProcessed($file)
    {

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $retorno = "";
        //var_dump($sheetData);

        foreach ($sheetData as $linha => $content){
            $erro = "";
            if($content['E'] !== "CPF"){

                $cpf = Helpers\formataCPF($content['E']);
                $dataAssociate = Associate::where('assoc_cpf', $cpf)->get();
                $dataAssociateCount = $dataAssociate->count();

                /*
                var_dump($dataAssociate);die;
                print_r(json_decode($dataAssociate[0]['id']));
                echo '<hr />';
                echo($cpf);
                die();
                */

                if($dataAssociateCount == 0){

                    if($content['J'] == "") {
                        $erro .= '- Campo e-mail (coluna J) não pode ser em branco';
                    }

                    $tipoAssocModel = Typeassociate::select('*')
                        ->where('tipassoc_nome','=', $content['I'])
                        ->get();

                    $content['I'] = $tipoAssocModel[0]->id;

                    if($content['I'] == 0) {
                        $erro .= '- Campo Tipo de Associado (coluna I) está inválido';
                    }

                    $classificationModel = Classification::select('*')
                        ->where('cla_nome', '=',$content['K'])
                        ->get();

                    $content['K'] = $classificationModel[0]->id;

                    if($content['K'] == 0) {
                        $erro .= '- Campo Classificação (coluna K) está inválido';
                    }

                    $agenteModel = Agent::select('*')
                        ->where('ag_nome', '=',$content['N'])
                        ->get();

                    $content['N'] = $agenteModel[0]->id;

                    if($content['N'] == 0) {
                        $erro .= '- Campo Agente (coluna N) está inválido';
                    }

                    $dateBirthday =  explode('/', $content['D']);

                    $dateBirthdayFormated = implode('-', array_reverse($dateBirthday));

                    $formDataExplode = explode('/', $content['T']);

                    $dataFormated = implode('-', array_reverse($formDataExplode));

                    if($erro == ''){
                        Associate::create([
                            'assoc_nome' => $content['A'],
                            'assoc_identificacao' => $content['C'],
                            'assoc_matricula' => $content['B'],
                            'assoc_datanascimento' => $dateBirthdayFormated,
                            'assoc_cpf' => $cpf,
                            'assoc_rg' => $content['F'],
                            'assoc_sexo' => ucfirst(strtolower($content['G'])),
                            'assoc_profissao' => $content['H'],
                            'tipassoc_codigoid' => $content['I'],
                            'assoc_email' => $content['J'],
                            'cla_codigoid' => $content['K'],
                            'assoc_estadocivil' => ucfirst(strtolower($content['L'])),
                            'assoc_fone' => $content['M'],
                            'ag_codigoid' => $content['N'],
                            'assoc_cep' => $content['O'],
                            'assoc_endereco' => $content['P'],
                            'assoc_bairro' => $content['Q'],
                            'assoc_uf' => $content['R'],
                            'assoc_cidade' => $content['S'],
                            'assoc_dataativacao' => $dataFormated,
                            'assoc_contrato' => $content['U'],
                            'created_at' => date("Y-m-d H:i:s"),
                            ]);
                    }
                } else {
                    $erro .= "- CPF (".$cpf.") já existe na base de dados;<br />";
                }
            }// Column is CPF

            //se teve algum erro, registra para o retorno
            if($erro != ""){
                $retorno .= "<strong>Linha ".$linha.":</strong><br />".$erro."<hr />";
            }

        }// Foreach end

        if($retorno != ""){
            $responseData = [
                'status' => 'error',
                'msg' => $retorno,
            ];
        } else {
            $responseData = [
                'status' => 'success',
                'msg' => 'Arquivo processado com sucesso!',
            ];
        }

        return ($responseData);
    }

    public static function convenantsProcessed($file)
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $retorno = "";
        // fase de validação
        foreach ($sheetData as $line => $content){
            $erro = "";
            if($content['A'] == "" &&
                $content['B'] == "" &&
                $content['C'] == "" &&
                $content['D'] == "" &&
                $content['E'] == "" &&
                $content['F'] == "" &&
                $content['G'] == "" ){
                    continue;
                }
            if($content['A'] !== 'ID'){
                /* removida essa validação em 15/10/2024, substituída pela validação de ID
                $dataAssociado = Associate::where('assoc_nome', '=', $content['B'])->get();
                if(!isset($dataAssociado[0]['assoc_nome'])){
                    $erro .= "- Associado não localizado;<br />";
                }
                */
                $dataAssociado = Associate::where('assoc_identificacao', '=', $content['A'])->get();
                if(!isset($dataAssociado[0]['assoc_identificacao'])){
                    $erro .= "- Associado não localizado;<br />";
                }
                
                $dataConvenio = TypeCategoryConvenant::where('con_nome', '=', $content['C'])->get();
                if(!isset($dataConvenio[0]['con_nome'])){
                    $erro .= "- Convênio inválido;<br />";
                }

                if(!is_int($content['D']) || !$content['D'] > 0){
                    $erro .= "- Número de parcelas é inválido;<br />";
                }

                if(!is_numeric($content['E']) || !$content['E'] > 0){
                    $erro .= "- Valor da parcela inválido;<br />";
                }

                $dt = explode('/',strval($content['F']));
                if(!is_array($dt)){
                    $erro .= $dt."- Data de início inválida;<br />";
                } else {
                    if(strlen($dt[0]) < 2){
                        $dt[0] = '0'.$dt[0];
                    }
                    if(strlen($dt[1]) < 2){
                        $dt[1] = '0'.$dt[1];
                    }
                    $data = $dt[1].'/'.$dt[0]. '/'. $dt[2];
                    $d = DateTime::createFromFormat('d/m/Y', $data);
                    if(!$d || $d->format('d/m/Y') != $data){
                        $erro .= $data."- Data de início inválida;<br />";
                    }
                }

                if($content['G'] == ""){
                    $erro .= "- Contrato inválido;<br />";
                }



                //se teve algum erro, registra para o retorno
                if($erro != ""){
                    $retorno .= "<strong>Linha ".$line.":</strong><br />".$erro;
                }
            }
        }

        if($retorno != ""){
            return $retorno;
            die;
        }

        // fase de armazenamento
        foreach ($sheetData as $content){
            if($content['A'] !== 'ID'){
                /* removida essa validação em 15/10/2024, substituída pela validação de ID
                $dataAssociado = Associate::where('assoc_nome', '=', $content['B'])->get();
                */
                $dataAssociado = Associate::where('assoc_identificacao', '=', $content['A'])->get();
                
                if($dataAssociado[0]['assoc_nome']){
                    $valorTotal = intval($content['E']) * intval($content['D']);

                    $dataConvenio = TypeCategoryConvenant::where('con_nome', '=', $content['C'])->get();

                    $dt = explode('/',strval($content['F']));
                    if(strlen($dt[0]) < 2){
                        $dt[0] = '0'.$dt[0];
                    }
                    if(strlen($dt[1]) < 2){
                        $dt[1] = '0'.$dt[1];
                    }
                    $data = $dt[1].'/'.$dt[0]. '/'. $dt[2];


                    $dv = DateTime::createFromFormat('d/m/Y', $data);
                    $interval = new DateInterval('P'.intval($content['D']).'M');
                    $dv->add($interval);

                    $lancamento = Convenant::create([
                        'lanc_valortotal' => $valorTotal,
                        'lanc_numerodeparcela' => intval($content['D']),
                        'lanc_contrato' => ($content['G']),
                        'lanc_datavencimento' => $dv->format('Y-m-d'),
                        'con_codigoid' => $dataConvenio[0]['id'],
                        'assoc_codigoid' => $dataAssociado[0]['id'],
                        //'est_codigoid' => 2,
                    ]);



                    if($lancamento){
                        //cria um intervalo de 1 mês
                        $d = DateTime::createFromFormat('d/m/Y', $data);
                        $interval = new DateInterval('P1M');
                        for ($i = 1; $i <= intval($content['D']); $i++){
                            //adiciona o intervalo a cara repetição
                            $d->add($interval);

                            $dataCompetencia = Competence::where('com_nome', '=', $d->format('m/Y'))->get();

                            $parcelamento = Portion::create([
                                'par_numero' => $i,
                                'par_valor' => floatval($content['E']),
                                'lanc_codigoid' => $lancamento->id,
                                'par_vencimentoparcela' => $d->format('Y-m-d'),
                                'par_observacao' => 'Processado através da planilha',
                                'par_status' => 'Pendente',
                                'com_codigoid' => $dataCompetencia[0]['id'],
                                'par_equivalente' => $i,
                                'par_habilitasn' => 0
                            ]);
                        }

                    }

                }else{
                    return false;
                }
            }
        }

        if($spreadsheet){

            return 'success';
        }else{
            return 'Erro na leitura do arquivo';
        }

    }

    public static function verifyFile($request)
    {
        $responseData = "";

        //dd($request->file('file'));

        if($request->hasFile('file')){
            // se enviou o arquivo
            if($request->file('file')->getMimeType() != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                //extensão inválida
                $responseData = [
                    'status' => 'warning',
                    'msg' => 'Envie um arquivo .xlsx ',
                ];
            }
        } else {
            $responseData = [
                'status' => 'warning',
                'msg' => 'Envie um arquivo!',
            ];
        }

        return $responseData;
    }

    /**
     * File Process
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMonthlyPayment(Request $request)
    {
        $verify = $this->verifyFile($request);

        if($verify == ""){
            if($request->massive == "convenio"){

                $process = self::convenantsProcessed($request->file);

                if($process == 'success'){
                    $responseData = [
                        'status' => 'success',
                        'msg' => 'Arquivo de Convênios processado com sucesso!',
                    ];

                    return response()->json([$responseData], 200);
                } else {
                    $responseData = [
                        'status' => 'warning',
                        'msg' => $process,
                    ];

                    return response()->json([$responseData], 200);
                }

            } else if($request->massive == "associado"){

                $process = self::associateProcessed($request->file);

                return response()->json([$process], 200);
            }
        }else {
            $responseData = $verify;
            return response()->json([$responseData], 200);
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function renegotiation($portion_id, $convenants_id)
    {
        try {
            //Change Status Portion to renegociation
            self::changeStatusPortion($portion_id, 'Transferido');
            //Load Portion
            $portion = Portion::select('*', 'competencia.id AS com_codigoid')
                ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                ->where('lanc_codigoid', $convenants_id)
                ->latest('par_numero')
                ->first();

            //Load Convenants with portion
            $convenants = Convenant::where('id', $portion['lanc_codigoid'])->get();

            try {

                $newCompetenceExploded = explode('/', $portion['com_nome']);
                //Increment 1 mount
                $newCompetenceExploded[0] = $newCompetenceExploded[0] + 1;

                if($newCompetenceExploded[0] > 12){
                    $newCompetenceExploded[0] = $newCompetenceExploded[0] - 12;
                    $newCompetenceExploded[1] = $newCompetenceExploded[1] + 1;
                }

                $newCompetenceExploded[0] = str_pad($newCompetenceExploded[0], 2, '0', STR_PAD_LEFT);

                /**
                 * Verify compentece exists
                 */

                $newCompetence = self::verifyCompetent($newCompetenceExploded[0].'/'.$newCompetenceExploded[1]);

                /**
                 * Select Portion have a Lancamento ID
                 * Insert a new Portion
                 */

                $modelPortion = new Portion();

                $modelPortion->par_valor       = $portion["par_valor"];
                $modelPortion->lanc_codigoid   = $convenants_id;
                $modelPortion->par_numero      = $portion["par_numero"] + 1;
                $modelPortion->par_equivalente = $portion["par_numero"] + 1;
                $modelPortion->com_codigoid    = $newCompetence[0]->id;
                $modelPortion->par_status      = 'Pendente';

                $modelPortion->save();

                try {
                    $day = date('d');

                    //Change Due Date from Lancamento table
                    Convenant::where('id', $portion['lanc_codigoid'])
                        ->update([
                            'lanc_datavencimento' =>$newCompetenceExploded[1].'-'. $newCompetenceExploded[0] .'-'. $day,
                            'lanc_numerodeparcela' => $convenants[0]->lanc_numerodeparcela + 1
                        ]);

                    return response()->json(['status'=>'success', 'msg'=>'Parcela renegociada com sucesso!']);
                }catch (Exception $e){
                    return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
                }

            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLancamento(Request $request){

        try {
            Convenant::where('id', $request->idLancamento)
                ->update([
                    'lanc_contrato' => $request->contract,
                    'con_codigoid' => $request->convenants
                ]);

            return response()->json(['status'=>'success', 'msg'=>'Lançamento alterado com sucesso!']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateParcelas(Request $request){
        //die(json_decode($request->idparcelas)[0]);
        try {
            //edita as parcelas
            $parcelas_afetadas = Portion::whereIn('id', json_decode($request->idparcelas))
                ->whereIn('par_status', ['Pendente','Renegociado','Reparcelado','Transferido','Vencido'])
                ->update([
                    'par_valor' => str_replace(',','.', str_replace('.','', $request->valor)),
                ]);

            //edita o lançamento
            DB::statement('UPDATE lancamento l SET l.lanc_valortotal = (SELECT SUM(par_valor) FROM parcelamento p WHERE p.deleted_at IS NULL AND p.lanc_codigoid = l.id) WHERE l.id = '.$request->idLancamento);
            

            return response()->json(['status'=>'success', 'msg'=>'Parcelas alteradas com sucesso!']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }

    public function addParcela(Request $request){
        try {
            $parcela = Portion::where('lanc_codigoid', $request->idLancamento)->orderBy('par_numero','desc')->first();

            //$dateConvert = str_replace("/", "-", $request->firstPortion);
            $dateConvert = implode("-", array_reverse(explode("/", $request->firstPortion)));


            for ($i = 1; $request->number >= $i; $i++) {
                $competenceID = Competence::where('com_nome', '=', date('m/Y', strtotime($dateConvert.' +'.($i-1).'months')))->first();

                $portionModel = new Portion();

                $portionModel->par_numero = $parcela->par_numero + $i;
                $portionModel->par_valor = str_replace(',','.', str_replace('.','',$request->valor));
                $portionModel->lanc_codigoid = $request->idLancamento;
                $portionModel->par_vencimentoparcela = date('Y-m-d', strtotime($dateConvert.' +'.($i-1).'months'));
                $portionModel->par_observacao = '';
                $portionModel->par_status = 'Pendente';
                $portionModel->com_codigoid = $competenceID->id;
                $portionModel->par_equivalente = $parcela->par_numero + $i;
                $portionModel->par_habilitasn = 1;
                $portionModel->save();
            }

            //atualiza o lançamento
            DB::statement('UPDATE lancamento AS l, 
                                (SELECT SUM(p.par_valor) AS valor, COUNT(p.id) AS parcelas, MAX(p.par_vencimentoparcela) AS vencimento FROM parcelamento p WHERE p.lanc_codigoid = '.$request->idLancamento.' AND p.deleted_at IS NULL) AS par
                            SET l.lanc_valortotal = par.valor, l.lanc_numerodeparcela = par.parcelas, l.lanc_datavencimento = par.vencimento
                            WHERE l.id =  '.$request->idLancamento);

            return response()->json(['status'=>'success', 'msg'=>'Parcelas adicionadas com sucesso!']);
        } catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusParcelas(Request $request){
        //die(json_decode($request->idparcelas)[0]);
        try {
            //edita as parcelas
            $parcelas_afetadas = Portion::whereIn('id', json_decode($request->idparcelas))
                ->update([
                    'par_status' => $request->statusParc,
                ]);


            return response()->json(['status'=>'success', 'msg'=>'Parcelas alteradas com sucesso!']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $convenantModel = new Convenant();

        try {

            // convert date
            $dateConvert = str_replace("/", "-", $request->firstPortion);
            $dateConvert =  date('Y-m-d', strtotime($dateConvert));


            $currentMonth = explode("-", $dateConvert);

            $vcto = date('Y-m',strtotime($dateConvert.'+'.($request->number-1).' months'));
            /*
            $monthUpdated = intval($currentMonth[1]) + $request->number;
            $yearUpdated  = intval($currentMonth[0]);

            if ($monthUpdated > 12) {
                $yearUpdated++;
                $monthUpdated = $monthUpdated - 12;
            }
            */
            //create Convenants
            $convenantModel->lanc_valortotal = str_replace(',','.', $request->total);
            $convenantModel->lanc_numerodeparcela = $request->number;
            $convenantModel->con_codigoid = $request->convenants;
            $convenantModel->lanc_datavencimento = $vcto.'-10';
            $convenantModel->assoc_codigoid = $request->associate;
            $convenantModel->lanc_contrato = $request->contract;
            $convenantModel->save();

            //get last insert id to Convenios table
            $lasInsertIdConvenat = Convenant::latest('id')->first();

            //create portion
            for ($i = 1; $request->number >= $i; $i++) {

                    if($currentMonth[1] > 12) {
                        $currentMonth[0]++;
                        $currentMonth[1] = $currentMonth[1] - 12;
                    }

                    if($currentMonth[1] < 10){
                        $currentMonth[1] = str_pad($currentMonth[1],2, 0, STR_PAD_LEFT);
                    }

                    //get competencia redister id
                    /*
                    $competenceID = Competence::where('com_nome', '=', $currentMonth[1].'/'.$currentMonth[0])->first();

                    if($competenceID !== null) {
                    }
                    */
                    $competenceID = Competence::firstOrCreate(
                        ['com_nome' => $currentMonth[1].'/'.$currentMonth[0] ],
                        [
                            'com_datainicio' => $currentMonth[0].'-'.$currentMonth[1].'-01',
                            'com_datafinal' => date('Y-m-t', strtotime($currentMonth[0].'-'.$currentMonth[1].'-01'))
                         ]
                    );

                    $portionModel = new Portion();

                    $portionModel->par_numero = $i;
                    $portionModel->par_valor = str_replace(',','.', str_replace('.','',$request->portion));
                    $portionModel->lanc_codigoid = $lasInsertIdConvenat->id;
                    $portionModel->par_vencimentoparcela = $currentMonth[0].'-'.$currentMonth[1].'-10';
                    $portionModel->par_observacao = '';
                    $portionModel->par_status = 'Pendente';
                    $portionModel->com_codigoid = $competenceID->id;
                    $portionModel->par_equivalente = $i;
                    $portionModel->par_habilitasn = 1;
                    $portionModel->save();

                $currentMonth[1]++;

            }
            return response()->json(['status'=>'success', 'msg'=> 'Formulário salvo com sucesso']);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    public function remove(Request $request): JsonResponse
    {
        $err = '';
        $lancamentos = [];
        $parcelas = DB::select('SELECT * FROM parcelamento WHERE id IN ('.implode(',',$request->id).')');
        //dd($parcelas);

        foreach($parcelas as $parc){
            //verifico os lançamentos envolvidos
            $lancamentos[$parc->lanc_codigoid] = 1;
            
            //se não for pago deleto a parcela
            if($parc->par_status != 'Pago') {
                Portion::find($parc->id)->delete();
            } else {
                $err .= 'Parcela '.$parc->par_numero.', valor R$ '.$parc->par_valor.' está paga e não pode ser deletada.\n';
            }
        }

        //Atualiza os lançamentos
        foreach(array_keys($lancamentos) as $v){
            $resumo = DB::select('SELECT SUM(par_valor) as valor, COUNT(id) as parcelas FROM parcelamento p WHERE p.lanc_codigoid = '.$v.' AND p.deleted_at IS NULL');
            //dd($resumo);
            if($resumo[0]->parcelas > 0){
                Convenant::where('id', $v)
                ->update([
                    'lanc_valortotal' => $resumo[0]->valor,
                    'lanc_numerodeparcela' => $resumo[0]->parcelas,
                ]);

            } else {
                Convenant::find($v)->delete();
            }

        }

        if($err != ''){
            //$err .= 'Demais parcelas removidas com sucesso';
            return response()->json(['status'=>'warning', 'titulo'=> 'Existe(m) parcela(s) paga(s)', 'msg' => 'Alguma(s) parcela(s) esta(ão) paga(s) e não pode(m) ser deletada(s).']);
        } else {
            return response()->json(['status'=>'success', 'titulo'=> 'Sucesso', 'msg' => 'Parcela(s) removida(s) com sucesso']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dropBill(Request $request){
        $responseData = "";
        $tempoInicial = time();
        

        if($request->hasFile('file')){
            // se enviou o arquivo
            if($request->file('file')->getMimeType() != 'text/plain'){
                //extensão inválida
                $responseData = [
                    'status' => 'warning',
                    'msg' => 'Envie um arquivo .txt ',
                ];
            }
        } else {
            $responseData = [
                'status' => 'warning',
                'msg' => 'Envie um arquivo!',
            ];
        }

        if($responseData == ""){
            $ponteiro = fopen($request->file('file'), "r");

            if($ponteiro) {
                $numeroLinha = 1;
                $competenciaFormatada = $request->selCompetitionDropBill;
                $competenciaExplodida = explode("/", $competenciaFormatada);
                $linha = [];
                $responseMSG = '';

                //LÊ O ARQUIVO ATÉ CHEGAR AO FIM
                while (!feof($ponteiro)) {
                    $ln = fgets($ponteiro, 4096);
                    if(trim($ln) == ""){
                        continue;
                    }
                    $linha[$numeroLinha]['convenio'] = $request->typeArchive;
                    $linha[$numeroLinha]['competenciaFormatada'] = $competenciaFormatada;
                    $linha[$numeroLinha]['competenciaExplodida'] = $competenciaExplodida;

                    if($request->typeArchive == 'ipe') {
                        $linha[$numeroLinha]['numeroPadrao'] = substr($ln, 0, 8);
                        $linha[$numeroLinha]['referencia'] = substr($ln, 8, 2);
                        $linha[$numeroLinha]['pensionista'] = substr($ln, 10, 2);
                        $linha[$numeroLinha]['rubrica'] = substr($ln, 12, 3);
                        $linha[$numeroLinha]['especie'] = substr($ln, 15, 20);
                        $linha[$numeroLinha]['dataTermino'] = substr($ln, 35, 6);
                        $linha[$numeroLinha]['valorPagar'] = substr($ln, 41, 9);
                        $linha[$numeroLinha]['valorDescontatoEmFolha'] = substr($ln, 50, 9);
                        $linha[$numeroLinha]['valorRejeitado'] = substr($ln, 59, 9);
                        $linha[$numeroLinha]['situacao'] = substr($ln, 68, 30);
                        $linha[$numeroLinha]['motivoRejeicaoDireita']  = substr($ln, 98, 24);
                        $linha[$numeroLinha]['motivoRejeicaoEsquerda'] = substr($ln, 72, 5);
                        $linha[$numeroLinha]['cpf'] = substr($ln, 123, 11);
                        $linha[$numeroLinha]['contrato'] = trim(substr($ln, 134, 40));
                        $linha[$numeroLinha]['contatoFormatado'] = substr($ln, 134, 15);//parte do contrato
                        $linha[$numeroLinha]['oficio'] = substr($ln, 174, 9);
                        $linha[$numeroLinha]['dtDireto'] = substr($ln, 183, 6);
                        $linha[$numeroLinha]['valorRecolhido'] = substr($ln, 189, 9);
                    } else if($request->typeArchive == 'tesouro') {
                        $linha[$numeroLinha]['numeroPadrao'] = substr($ln, 0, 1);
                        $linha[$numeroLinha]['matricula'] = substr($ln, 0, 12);
                        $linha[$numeroLinha]['valordesconto'] = substr($ln, 49, 10);
                        $linha[$numeroLinha]['referencia'] = substr($ln, 15, 12);
                        $linha[$numeroLinha]['mensagem'] = substr($ln, 68, 54);
                        $linha[$numeroLinha]['mensagemMelhorada'] = strip_tags(trim(substr($ln, 68, 54)));
                    }

                    //print_r($linha); die;

                    //verifica os dados do arquivo
                    $arr_rtn = $this->validaArquivoBaixa($linha[$numeroLinha]);

                    foreach($arr_rtn as $k => $v){
                        $linha[$numeroLinha][$k] = $v;
                    }

                    if($linha[$numeroLinha]['msg']){
                        $responseMSG .= 'Linha '.$numeroLinha.': '.$linha[$numeroLinha]['msg'].'<br />';
                    }

                    $numeroLinha++;
                }

                //print_r($linha);

                // arquivo de diferenças
                /*
                if($request->typeArchive == 'ipe') {
                    $conteudoArquivoDif = "0".$competenciaExplodida[1].$competenciaExplodida[0]."CIRCULO OPERARIO FERROVIARIO RS - ARQUIVO DE DIFERENÇAS \n";

                    $diferencas = Portion::select('assoc_matricula', 'assoc_contrato', 'lanc_contrato', 'con_referencia', 'par_valor', DB::raw('SUM( par_valor ) AS somatorioparcelas'))
                                ->join('lancamento','parcelamento.lanc_codigoid','=','lancamento.id')
                                ->leftJoin('convenio','convenio.id','=','lancamento.con_codigoid')
                                ->leftJoin('associado','lancamento.assoc_codigoid','=','associado.id')
                                ->join('competencia','competencia.id','=','parcelamento.com_codigoid')
                                ->leftJoin('classificacao','classificacao.id','=','associado.id')
                                ->where('associado.cla_codigoid','=','15')
                                ->where('associado.assoc_ativosn','=','1')
                                ->where('parcelamento.par_habilitasn','=','1')
                                ->where('competencia.com_nome','=', $competenciaFormatada)
                                ->whereIn('convenio.con_referencia',["DIVERSOS","MENSALIDADE","EMPRESTIMO"])
                                ->where('parcelamento.par_status','=',"Vencido")
                                ->groupBy(DB::raw('IF(convenio.con_referencia IN ("DIVERSOS","EMPRESTIMO"), lanc_contrato, parcelamento.id)'))
                                ->orderBy('assoc_matricula','asc')
                                ->get()
                                ;
                    //dd($diferencas->toSql(),$diferencas->getBindings());
                    foreach ($diferencas as $d){
                        if($d->con_referencia == 'DIVERSOS'){
                            $conteudoArquivoDif .= $d->assoc_matricula.str_pad($d->lanc_contrato,40,' ').str_pad(strip_tags(trim($d->con_referencia)),20,' ').number_format($d->somatorioparcelas,2,'','')."\n";
                        }elseif($d->con_referencia == 'MENSALIDADE'){
                            $conteudoArquivoDif .= $d->assoc_matricula.str_pad($d->assoc_contrato,40,' ').str_pad(strip_tags(trim($d->con_referencia)),20,' ').number_format($d->par_valor,2,'','')."\n";
                        }elseif($d->con_referencia == 'EMPRESTIMO'){
                            $conteudoArquivoDif .= $d->assoc_matricula.str_pad($d->lanc_contrato,40,' ').str_pad(strip_tags(trim($d->con_referencia)),20,' ').number_format($d->somatorioparcelas,2,'','')."\n";
                        }
                    }

                    $nomeArquivo = 'arqDif-IPE-'.$competenciaExplodida[0].'-'.$competenciaExplodida[1].'-'.date('YmdHi').'.txt';

                } else if($request->typeArchive == 'tesouro') {
                    $conteudoArquivoDif = "0".$competenciaExplodida[1].$competenciaExplodida[0]."CIRCULO OPERARIO FERROVIARIO RS - ARQUIVO DE DIFERENÇAS - TESOURO \n";

                    $diferencas = Portion::select('id','par_status','assoc_matricula','convenio.con_referencia','assoc_nome')
                                ->join('lancamento','parcelamento.lanc_codigoid','=','lancamento.id')
                                ->leftJoin('convenio','convenio.id','=','lancamento.con_codigoid')
                                ->leftJoin('associado','lancamento.assoc_codigoid','=','associado.id')
                                ->join('competencia','competencia.id','=','parcelamento.com_codigoid')
                                ->leftJoin('classificacao','classificacao.id','=','associado.id')
                                ->where('parcelamento.par_habilitasn','=','1')
                                ->where('associado.cla_codigoid','=','18')
                                ->where('competencia.com_nome','=', $competenciaFormatada)
                                ->whereIn('parcelamento.par_status',["Vencido","Pendente"])
                                ->orderBy('associado.assoc_nome','asc')
                                ->get()
                                ;

                    //dd($diferencas->toSql(),$diferencas->getBindings());
                    foreach ($diferencas as $d){
                        if($d->par_status == 'Pendente'){
                            Portion::where('id', $parc->id)
                                ->update([
                                    'par_status' => 'Vencido'
                                ]);
                        }

                        $conteudoArquivoDif .= $d->assoc_matricula." ".$d->con_referencia." ".$d->assoc_nome." ".$d->assoc_nome."\n";
                    }

                    $nomeArquivo = 'arqDif-IPE-'.$competenciaExplodida[0].'-'.$competenciaExplodida[1].'-'.date('YmdHi').'.txt';
                } // fim arquivo de diferenças

                // grava o arquivo de diferenças
                Storage::disk('public')->put($nomeArquivo, $conteudoArquivoDif,'public');
                */

                //$arq = asset('storage/'.$nomeArquivo);

                $tempo = gmdate("H:i:s", (time() - $tempoInicial));
                
                //<strong>Arquivo de diferenças:</strong> <a href="'.Storage::disk('public')->url($nomeArquivo).'" target="_blank">[baixar]</a>
                $responseMSG .= '<br /><small>Processado em '.$tempo.'</small>';

                $responseData = [
                    'status' => 'success',
                    'msg' => $responseMSG,
                ];

                return response()->json([$responseData], 200);

            }else {
                return response()->json(['status' => 'warning', 'msg' => 'Não foi possível abrir o arquivo'], 200);
            }

        }else{
            return response()->json([$responseData], 200);
        }
    }

    /**
     * @param Array $linha
     * @return Array $arr_rtn
     */
    public function validaArquivoBaixa($linha){
        ini_set('max_execution_time', '-1');
        $arr_rtn = [
            'msg' => '',
            'parcelas_encontradas' => 0,
        ];

        // verifica o convênio
        if($linha['convenio'] == 'ipe'){
            // verifica se este contrato existe na competencia selecionada
            $parcelamento = Portion::select('parcelamento.id')
                            ->join('competencia','competencia.id','=','parcelamento.com_codigoid')
                            ->join('lancamento','parcelamento.lanc_codigoid','=','lancamento.id')
                            ->leftJoin('associado','lancamento.assoc_codigoid','=','associado.id');
            //->leftJoin('classificacao','classificacao.id','=','associado.id')

            if($linha['contatoFormatado'] == "CTR_IPERGS_MENS"){
                $parcelamento->leftJoin('convenio','convenio.id','=','lancamento.con_codigoid')
                            ->where('associado.assoc_contrato','=', $linha['contrato'])
                            ->where('convenio.con_referencia','=',"MENSALIDADE");
            }else{
                $parcelamento->where('lancamento.lanc_contrato','=', $linha['contrato']);
            };

            $parcelamento->where('associado.cla_codigoid','=','15')
                        ->where('associado.assoc_ativosn','=','1')
                        ->where('parcelamento.par_habilitasn','=','1')
                        ->where('competencia.com_nome','=',$linha['competenciaFormatada'])
                        ->where('parcelamento.par_status','=','Pendente');

            //dd($parcelamento->toSql(),$parcelamento->getBindings());
            /*
            if($linha['valorRejeitado'] == $linha['valorPagar']) {
                $arr_rtn['msg'] = 'Valor rejeitado ('.$linha['valorRejeitado'].') é igual ao com o a pagar ('.$linha['valorPagar'].')';
                $par_status = 'Pendente';
            } else
            */
            if (trim($linha['motivoRejeicaoDireita']) == "Insufici?ncia de L?quido"){
                $arr_rtn['msg'] = 'Insuficiencia de saldo';
                $par_status = 'Vencido';
            } elseif(trim($linha['motivoRejeicaoEsquerda']) == "Obito"){
                $arr_rtn['msg'] = 'Óbito';
                $par_status = 'Vencido';
            } elseif($linha['valorRejeitado'] > 0){
                $arr_rtn['msg'] = 'Existe um valor Valor rejeitado ('.$linha['valorRejeitado'].')';
                $par_status = 'Pendente';
            } else {
                $arr_rtn['msg'] = '';
                $par_status = 'Pago';
            }

            $quantidade = $parcelamento->count();
            $arr_rtn['parcelas_encontradas'] = $quantidade;

            if($quantidade != 0){
                $parcelas = $parcelamento->get();
                foreach ($parcelas as $parc){
                    Portion::where('id', $parc->id)
                        ->update([
                            'par_status' => $par_status
                        ]);
                }
            } else {
                $arr_rtn['msg'] = 'Nenhuma parcela encontrada';
            }

        } // fim arquivo ipe
        if($linha['convenio'] == 'tesouro'){
            if($linha['mensagemMelhorada'] == ""){
                $parcelamento = Portion::select('parcelamento.id')
                                ->join('competencia','competencia.id','=','parcelamento.com_codigoid')
                                ->join('lancamento','parcelamento.lanc_codigoid','=','lancamento.id')
                                ->leftJoin('associado','lancamento.assoc_codigoid','=','associado.id')
                                ->leftJoin('convenio','convenio.id','=','lancamento.con_codigoid')
                                ->where('parcelamento.par_habilitasn','=','1')
                                ->where('associado.assoc_identificacao','=', $linha['matricula'])
                                ->where('associado.assoc_ativosn','=','1')
                                ->where('competencia.com_nome','=',$linha['competenciaFormatada'])
                                ->where('parcelamento.par_status','=','Pendente')
                                ->where('convenio.con_referencia','=',$linha['referencia']);
                /*
                if($linha['referencia'] == "SEG MARITIMA") {
                    $parcelamento->where('associado.cla_codigoid','=','44');
                } else if($linha['referencia'] == "MENSALIDADE ") {
                    $parcelamento->where('associado.cla_codigoid','=','42');
                }
                */

                //dd($parcelamento->toSql(),$parcelamento->getBindings());

                $quantidade = $parcelamento->count();
                $arr_rtn['parcelas_encontradas'] = $quantidade;
                $par_status = 'Pago';
                $arr_rtn['msg'] = '';

                if($quantidade > 0){
                    $parcelas = $parcelamento->get();
                    foreach ($parcelas as $parc){
                        Portion::where('id', $parc->id)
                            ->update([
                                'par_status' => $par_status
                            ]);
                    }
                } else {
                    $arr_rtn['msg'] = 'Nenhuma parcela encontrada';
                }

            } else {
                $arr_rtn['msg'] = $linha['mensagemMelhorada'];
            }


        } // fim arquivo tesouro


        return $arr_rtn;
    }

    public function editParcelaObs(Request $request){
        //die(json_decode($request->idparcelas)[0]);
        try {
            //edita as parcelas
            $parcelas_afetadas = Portion::where('id', $request->idParcela)
                ->update([
                    'par_observacao' => $request->par_observacao,
                ]);


            return response()->json(['status'=>'success', 'msg'=>'Parcela alterada com sucesso!']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg' => $e->getMessage()]);
        }
    }

}
