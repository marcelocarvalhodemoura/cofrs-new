<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Associate;

use App\Models\Competence;
use App\Models\Convenant;
use App\Models\Portion;

use App\Models\Typeassociate;
use App\Models\Classification;

use App\Models\TypeCategoryConvenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use SimpleXLSX;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
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
        $competitionList = Competence::all();
        $agreementList = Agreement::orderBy('con_nome', 'asc')->get();


        $data = [
            'category_name' => 'covenants',
            'page_name' => 'covenants',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        $lists = [
            'associateList'=> $associateList,
            'competitionList'=> $competitionList,
            'agreementList' => $agreementList,
        ];

        return view('covenants.list', $lists)->with($data);
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
                $convenantList = Convenant::select('*', 'lancamento.id AS lanc_codigoid')
                    ->join('associado', 'associado.id', '=', 'lancamento.assoc_codigoid')
                    ->join('convenio', 'convenio.id', '=', 'lancamento.con_codigoid')
                    ->where($dynamicWhere)
                    ->get();

                foreach ($convenantList as $index => $item) {
                    //load portion within lanc_codigoid iqual to id from lancamento
                    $convenantList[$index]['portion'] = Portion::select('*', 'parcelamento.id AS par_codigoid')
                        ->join('competencia', 'competencia.id', '=', 'parcelamento.com_codigoid')
                        ->where('parcelamento.lanc_codigoid', $item->lanc_codigoid)
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
    protected function changeStatusPortion($id, $status){
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
    public function changePayment(Request $request){
        $arrayId = $request->id;

        foreach ($arrayId as $id){
            try{

                $affected = self::changeStatusPortion($id, 'Pago');

                if($affected > 0){
                    return response()->json(['status'=>'success', 'msg'=> 'Parcela Quitada com sucesso!']);
                }
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }
        }

    }

    /**
     * @param $compentence
     * @return mixed
     */
    protected function verifyCompetent($compentence)
    {
        $competenceList = Competence::where(['com_nome'=>$compentence])->get();

        if(isEmpty($competenceList)){

            $competenceModel = new Competence();

            $newData = explode('/', $compentence);

            $beginMouth = $newData[0] - 1;
            $beginYear = $newData[1];

            if($beginMouth === 0 ){
                $beginMouth = 12;
                $beginYear = $beginYear - 1;
            }

            try {
                $competenceModel->com_nome = $compentence;
                $competenceModel->com_datainicio = $beginYear.'-'. $beginMouth .'-11';
                $competenceModel->com_datafinal = $newData[1].'-'.$newData[0].'-10';
                $competenceModel->save();

                return Competence::where(['com_nome'=>$compentence])->get();
            }catch (Exception $exception){
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

                if(empty(json_decode($dataAssociate)) === true){

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
                                'par_valor' => intval($content['E']),
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

    public static function verifyFile($request){
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
            //create Convenants
            $convenantModel->lanc_valortotal = str_replace(',','.', $request->total);
            $convenantModel->lanc_numerodeparcela = $request->number;
            $convenantModel->con_codigoid = $request->convenants;
            $convenantModel->lanc_datavencimento = date('Y-m-d', strtotime($request->duedate));;
            $convenantModel->assoc_codigoid = $request->associate;
            //$convenantModel->est_codigoid = 2;

            $convenantModel->save();
            $lasInsertIdConvenat = Convenant::latest('id')->first();

            try {

                $currentMonth = explode("-", date('Y-m-d'));

                $monthUpdated = intval($currentMonth[1]);
                $yearUpdated  = intval($currentMonth[0]);

                //create portion
                for ($i = 1; $request->number >= $i; $i++) {

                    $monthUpdated++;

                    if ($monthUpdated > 12) {
                        $monthUpdated = 01;
                        $yearUpdated++;
                    }

                    if ($monthUpdated < 10) {
                        $monthUpdated = "0".$monthUpdated;
                    }

                    $competenceID = Competence::where('com_nome', '=', $monthUpdated.'/'.$yearUpdated)->get();

                    if((int)$competenceID[0]['id'] > 0 ){
                        $portionModel = new Portion();

                        $portionModel->par_numero = $i;
                        $portionModel->par_valor = str_replace(',','.',$request->portion);
                        $portionModel->lanc_codigoid = $lasInsertIdConvenat['id'];
                        $portionModel->par_vencimentoparcela = $yearUpdated.'-'.$monthUpdated.'-10';
                        $portionModel->par_observacao = '';
                        $portionModel->par_status = 'Pendente';
                        $portionModel->com_codigoid = $competenceID[0]['id'];
                        $portionModel->par_equivalente = $i;
                        $portionModel->par_habilitasn = 0;

                        $portionModel->save();


                    }


                }
                return response()->json(['status'=>'success', 'msg'=> 'Formulário salvo com sucesso']);
            }catch (Exception $e){
                return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
            }

        }catch (Exception $e){
            return response()->json(['status'=>'error', 'msg'=> $e->getMessage()]);
        }

    }
}
