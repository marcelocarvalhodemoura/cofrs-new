<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Agreement;
use App\Models\Associate;

use App\Models\Competence;
use App\Models\Convenant;
use App\Models\Portion;

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

                        $valuePortion = str_pad($diversosTotal[0].$diversosTotal[1], 27 ,  "0", STR_PAD_RIGHT);

//                        $bigestDate = explode("-", $convenantDiverso->datamaior);

                        $contentFile .= "D" . str_pad($convenantDiverso->assoc_matricula, 12, "0", STR_PAD_LEFT). $reference . $contract .$request->yearCompetence.$request->monthCompetence.'0000'.$valuePortion."\r\n";


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

                        $valuePortionMonthlyPayment = str_pad($monthlyPaymentTotal[0].$monthlyPaymentTotal[1], 27 ,  "0", STR_PAD_RIGHT);

                        $contentFile .= "D".str_pad($convenantMonthlyPayment->assoc_matricula, 12, "0", STR_PAD_LEFT).$reference.$contractMonthPay.'0000000000'.$valuePortionMonthlyPayment."\r\n";

                    }//end to Foreach Monthly Payment
                }

                if($typeConvenant === 'EMPRESTIMO'){
                    $loanConvenant = self::typeReferenceAgrouped($request->monthCompetence . '/' . $request->yearCompetence, 'EMPRESTIMO');


                    //List Monthly Payment
                    foreach($loanConvenant as $loan){

                        $contractMonthPay = str_pad($loan->lanc_contrato, 40, " ", STR_PAD_RIGHT);
                        $reference = str_pad($loan->con_referencia, 20, " ", STR_PAD_RIGHT);
                        //Format money to 2 decimal
                        $monthlyPaymentTotal = number_format($loan->valor_total_emprestimo, 2, '.', '');
                        $monthlyPaymentTotal = explode('.', $monthlyPaymentTotal);

                        $valuePortionMonthlyPayment = str_pad($monthlyPaymentTotal[0].$monthlyPaymentTotal[1], 27 ,  "0", STR_PAD_RIGHT);

                        $contentFile .= "D".str_pad($loan->assoc_matricula, 12, "0", STR_PAD_LEFT).$reference.$contractMonthPay.$request->yearCompetence.$request->monthCompetence.'0000'.$valuePortionMonthlyPayment."\r\n";

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
                $referenceSql = Portion::select('*')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->where('cla_codigoid', '=', 15)
                    ->where('par_numero', '=', 1)
                    ->orderBy('assoc_matricula', 'asc')
                    ->get();
                break;

            case 'DIVERSOS':
                $referenceSql = Portion::select('*')
                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->where('cla_codigoid', '=', 15)
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->selectRaw('SUM(par_valor) as valor_total_diversos')
                    ->selectRaw('MAX(lancamento.lanc_datavencimento) as datamaior')
                    ->groupBy('assoc_matricula')
                    ->orderBy('assoc_matricula', 'asc')
                    ->get();
                break;
            case 'EMPRESTIMO':
                $referenceSql = Portion::select('*')
                    ->join('lancamento', 'lancamento.id', '=', 'parcelamento.lanc_codigoid')
                    ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->where('cla_codigoid', '=', 15)
                    ->where('com_nome', '=', $competenceName)
                    ->where('con_referencia', '=', $reference)
                    ->selectRaw('SUM(par_valor) as valor_total_emprestimo')
                    ->groupBy('assoc_matricula')
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

            if($request->post('selAssociate')){
                $dynamicWhere[] = ['lancamento.assoc_codigoid', '=', $request->selAssociate];
            }

            if($request->post('selAgreement')){
                $dynamicWhere[] = ['lancamento.con_codigoid','=', $request->selAgreement];
            }

            try {
                //load Convenants from table lancamentos
                $convenantList = Convenant::select(
                    'assoc_nome',
                    'assoc_cpf',
                    'con_nome',
                    'con_referencia',
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
                        'parcelamento.id AS par_codigoid'
                        )
                        ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                        ->where('parcelamento.lanc_codigoid', $item->lanc_codigoid)
                        ->where('parcelamento.deleted_at','"0000-00-00 00:00:00"')
                        ->get();
                }

                foreach($convenantList as $k => $v){
                    /*
                    echo '<pre>';
                    echo($k.' - '.$v['portion']);

                    if($v['portion'] == '' || count($v['portion']) == 0){
                        unset($convenantList[$k]);
                    }
                    */
                }
                //array_values($convenantList);

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

        foreach ($sheetData as $content){

            if($content['E'] !== "CPF"){

                $dataAssociate = Associate::where('assoc_cpf', $content['E'])->get();

                // var_dump(json_decode($dataAssociate));
                // die('associateProcessed');

                if(empty(json_decode($dataAssociate)) !== true){

                    $tipoAssocModel = Typeassociate::select('*')
                        ->where('tipassoc_nome','=', $content['I'])
                        ->get();

                    $content['I'] = $tipoAssocModel[0]->id;

                    $classificationModel = Classification::select('*')
                        ->where('cla_nome', '=',$content['K'])
                        ->get();

                    $content['K'] = $classificationModel[0]->id;

                    $dateBirthday =  explode('/', $content['D']);

                    $dateBirthdayFormated = implode('-', array_reverse($dateBirthday));

                    $formDataExplode = explode('/', $content['T']);

                    $dataFormated = implode('-', array_reverse($formDataExplode));

                    try{
                        $associateModel = new Associate();

                        $associateModel->assoc_nome = $content['A'];
                        $associateModel->assoc_identificacao = $content['B'];
                        $associateModel->assoc_matricula = $content['C'];
                        $associateModel->assoc_datanascimento = $dateBirthdayFormated;
                        $associateModel->assoc_cpf = $content['E'];
                        $associateModel->assoc_rg = $content['F'];
                        $associateModel->assoc_sexo = $content['G'];
                        $associateModel->assoc_profissao = $content['H'];
                        $associateModel->tipassoc_codigoid = $content['I'];
                        $associateModel->assoc_email = $content['J'];
                        $associateModel->cla_codigoid = $content['K'];
                        $associateModel->assoc_estadocivil = $content['L'];
                        $associateModel->assoc_fone = $content['M'];
                        $associateModel->assoc_agencia = $content['N'];
                        $associateModel->assoc_cep = $content['O'];
                        $associateModel->assoc_endereco = $content['P'];
                        $associateModel->assoc_bairro = $content['Q'];
                        $associateModel->assoc_uf = $content['R'];
                        $associateModel->assoc_cidade = $content['S'];
                        $associateModel->assoc_dataativacao = $dataFormated;
                        $associateModel->assoc_contrato = $content['U'];

                        $associateModel->save();

                    }catch(Exception $e){
                        return  response()->json($e, 400);
                    }
                }
            }// Column is CPF

        }// Foreach end

        $responseData = [
            'status' => 'success',
            'msg' => 'Arquivo processado com sucesso!',
        ];

        return response()->json([$responseData], 200);
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
                $dataAssociado = Associate::where('assoc_nome', '=', $content['B'])->get();
                if(!isset($dataAssociado[0]['assoc_nome'])){
                    $erro .= "- Associado não localizado;<br />";
                } elseif($dataAssociado[0]['assoc_contrato'] != ""){
                    $erro .= "- Associado já possui um contrato;<br />";
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

                $dataAssociado = Associate::where('assoc_nome', '=', $content['B'])->get();

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
                        'lanc_datavencimento' => $dv->format('Y-m-d'),
                        'con_codigoid' => $dataConvenio[0]['id'],
                        'assoc_codigoid' => $dataAssociado[0]['id'],
                        //'est_codigoid' => 2,
                    ]);



                    if($lancamento){
                        //cria um intervalo de 1 mês
                        $d = DateTime::createFromFormat('d/m/Y', $data);
                        $interval = new DateInterval('P1M');
                        for ($i = 0; $i < intval($content['D']); $i++){
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

                if($process == true){
                    $responseData = [
                        'status' => 'success',
                        'msg' => 'Arquivo de Convênios processado com sucesso!',
                    ];

                    return response()->json([$responseData], 200);
                }
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
    public function store(Request $request)
    {
        $convenantModel = new Convenant();

        try {

            // convert date
            $dateConvert = str_replace("/", "-", $request->firstPortion);
            $dateConvert =  date('Y-m-d', strtotime($dateConvert));


            $currentMonth = explode("-", $dateConvert);

            $monthUpdated = intval($currentMonth[1]) + $request->number;
            $yearUpdated  = intval($currentMonth[0]);

            if ($monthUpdated > 12) {
                $yearUpdated++;
                $monthUpdated = $monthUpdated - 12;
            }

            //create Convenants
            $convenantModel->lanc_valortotal = str_replace(',','.', $request->total);
            $convenantModel->lanc_numerodeparcela = $request->number;
            $convenantModel->con_codigoid = $request->convenants;
            $convenantModel->lanc_datavencimento = $yearUpdated.'-'.$monthUpdated.'-10';
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
                    $competenceID = Competence::where('com_nome', '=', $currentMonth[1].'/'.$currentMonth[0])->first();


                    if($competenceID !== null) {
                        $portionModel = new Portion();

                        $portionModel->par_numero = $i;
                        $portionModel->par_valor = str_replace(',','.',$request->portion);
                        $portionModel->lanc_codigoid = $lasInsertIdConvenat->id;
                        $portionModel->par_vencimentoparcela = $currentMonth[0].'-'.$currentMonth[1].'-10';
                        $portionModel->par_observacao = '';
                        $portionModel->par_status = 'Pendente';
                        $portionModel->com_codigoid = $competenceID->id;
                        $portionModel->par_equivalente = $i;
                        $portionModel->par_habilitasn = 0;
                        $portionModel->save();
                    }

                $currentMonth[1]++;

            }
            return response()->json(['status'=>'success', 'msg'=> 'Formulário salvo com sucesso']);
        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }

    public function remove(Request $request): JsonResponse
    {


        foreach($request->id as $id){
            try {
                $portion = Portion::find($id);

                $convenants = Portion::where('lanc_codigoid', '=', $portion->lanc_codigoid)->get();

                if($portion->par_status === 'Pago') {
                    response()->json(['status'=>'error', 'msg'=> 'Não é possível excluir um lançamento pago']);
                } else {
                    $portion->delete();

                    $convenants = Portion::where('lanc_codigoid', '=', $portion->lanc_codigoid)->get();

                    if(count($convenants) === 0){
                        Convenant::find($portion->lanc_codigoid)->delete();
                    }
                }





            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }
        }

        return response()->json(['status'=>'success', 'msg'=> 'Parcela removida com sucesso']);

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
                $numeroLinha = 0;
                $competenciaFormatada = $request->selCompetitionDropBill;
                $competenciaExplodida = explode("/", $competenciaFormatada);
                $linha = [];
                $responseMSG = '';

                //LÊ O ARQUIVO ATÉ CHEGAR AO FIM
                while (!feof($ponteiro)) {
                    $ln = fgets($ponteiro, 4096);
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
                        $linha[$numeroLinha]['contrato'] = substr($ln, 134, 40);
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

                //$arq = asset('storage/'.$nomeArquivo);

                $tempo = gmdate("H:i:s", (time() - $tempoInicial));

                $responseMSG .= '<strong>Arquivo de diferenças:</strong> <a href="'.Storage::disk('public')->url($nomeArquivo).'" target="_blank">[baixar]</a><br /><small>Processado em '.$tempo.'</small>';

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

            // valida o
            if($linha['valorRejeitado'] == $linha['valorPagar']) {
                $arr_rtn['msg'] = 'Valor pago não confere';
                $par_status = 'Pendente';
            } elseif (trim($linha['motivoRejeicaoDireita']) == "Insufici?ncia de L?quido"){
                $arr_rtn['msg'] = 'Valor insuficiente';
                $par_status = 'Vencido';
            } elseif(trim($linha['motivoRejeicaoEsquerda']) == "Obito"){
                $arr_rtn['msg'] = 'Óbito';
                $par_status = 'Vencido';
            } else {
                $arr_rtn['msg'] = '';
                $par_status = 'Pago';
            }

            $quantidade = $parcelamento->count();
            $arr_rtn['parcelas_encontradas'] = $quantidade;

            if($quantidade == 0){
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
                                ->where('associado.assoc_matricula','=', $linha['matricula'])
                                ->where('associado.assoc_ativosn','=','1')
                                ->where('competencia.com_nome','=',$linha['competenciaFormatada'])
                                ->where('parcelamento.par_status','=','Pendente')
                                ->where('convenio.con_referencia','=',$linha['referencia']);

                if($linha['referencia'] == "SEG MARITIMA") {
                    $parcelamento->where('associado.cla_codigoid','=','44');
                } else if($linha['referencia'] == "MENSALIDADE ") {
                    $parcelamento->where('associado.cla_codigoid','=','42');
                }

                $quantidade = $parcelamento->count();
                $arr_rtn['parcelas_encontradas'] = $quantidade;

                $par_status = 'Pago';
                $arr_rtn['msg'] = '';

                if($quantidade == 0){
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

}
