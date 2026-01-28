<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use \App\Http\Controllers\AssociateController;
use \App\Http\Controllers\TypeAssociateController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConvenantController;
use App\Http\Controllers\CategoryConvenantController;
use App\Http\Controllers\TypeCategoryConvenantController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\MigracaoController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\MailController;

use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//rota de teste
Route::get('/teste', [ConvenantController::class, 'darBaixaAutomatica']);


Route::get('', function () {
    // $category_name = 'auth';
    $data = [
        'category_name' => 'auth',
        'page_name' => 'auth_boxed',
        'has_scrollspy' => 0,
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    // $pageName = 'auth_default';
    return view('pages.authentication.auth_login')->with($data);
})->name('login');

Route::post('/recovery', [UserController::class, 'recovery']);

Route::get('/esqueci', function () {
    // $category_name = 'auth';
    $data = [
        'category_name' => 'auth',
        'page_name' => 'auth_boxed',
        'has_scrollspy' => 0,
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    // $pageName = 'auth_default';
    return view('pages.authentication.auth_pass_recovery')->with($data);
})->name('login');
Route::post('/auth', [UserController::class, 'authentication']);



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/aNAverbados', [DashboardController::class, 'aNAverbados']);




/**
 * User routes
 */
Route::get('/users', [UserController::class, 'index']);
Route::get('/logout', [UserController::class, 'logOut'])->name('logout');
Route::get('/users/load/{id}', [UserController::class, 'getUser']);
Route::post('/users/delete/{id}', [UserController::class, 'delete']);
Route::post('/users/pass/{id}', [UserController::class, 'forgotPassword']);
Route::post('/users/store', [UserController::class, 'store']);
Route::post('/users/update/{id}', [UserController::class, 'update']);
Route::get('/users/profile/{id}', [UserController::class, 'profile']);




/**
 * Associates Routes
 */
Route::get('/associates', [AssociateController::class, 'index'])->name('associates');
Route::post('/associates/store', [AssociateController::class, 'store']);
Route::post('/associates/delete/{id}', [AssociateController::class, 'delete']);
Route::get('/associates/instalment/{id}', [AssociateController::class, 'associateConvenants']);
Route::get('/associates/{id}', [AssociateController::class, 'getAssociate']);
Route::post('/associates/dependents/store', [AssociateController::class, 'storeDependets']);
Route::get('/associates/dependents/{id}', [AssociateController::class, 'getDependents']);
Route::post('/associates/dependents/remove/{id}', [AssociateController::class, 'deleteDependents']);

/**
 * Type Associates Routes
 */
Route::get('/typeassociate/list', [TypeAssociateController::class, 'list']);

/**
 * Classification Route
 */
Route::get('/classification/list', [ClassificationController::class, 'list']);


/**
 * Agent Route
 */

Route::get('/agent/list', [AgentController::class, 'list']);

/**
 * Convenants Routes
 */
Route::get('/covenants', [ConvenantController::class, 'index']);
Route::post('/convenants/store', [ConvenantController::class, 'store']);
Route::post('/convenants/list', [ConvenantController::class, 'getCovenants']);
Route::get('/covenants/associate/list', [ConvenantController::class, 'getAssociates']);
Route::post('/convenats/portion', [ConvenantController::class, 'changePayment']);
Route::get('/convenants/renegotiation/{id}/{id2}', [ConvenantController::class, 'renegotiation']);
Route::post('/convenants/remove', [ConvenantController::class, 'remove']);
Route::get('/convenants/monthly', [ConvenantController::class, 'getMonthlyPayment']);
Route::post('/convenants/monthly/add', [ConvenantController::class, 'storeMonthlyPayment']);
Route::post('/convenants/file/create', [ConvenantController::class, 'createFile']);
Route::post('/convenants/dropBill', [ConvenantController::class, 'dropBill']);
Route::post('/convenants/updateLancamento', [ConvenantController::class, 'updateLancamento']);
Route::post('/convenants/updateParcelas', [ConvenantController::class, 'updateParcelas']);
Route::post('/convenants/updateStatusParcelas', [ConvenantController::class, 'updateStatusParcelas']);
Route::post('/convenants/addParcela', [ConvenantController::class, 'addParcela']);
Route::post('/convenants/editParcelaObs', [ConvenantController::class, 'editParcelaObs']);

Route::get('/processArchive', [ConvenantController::class, 'processArchive']);


/**
 * Category Convenants
 */
Route::get('/categories-convenants', [CategoryConvenantController::class, 'index']);
Route::post('/categories-convenants/store', [CategoryConvenantController::class, 'store']);
Route::get('/categories-convenants/load/{id}', [CategoryConvenantController::class, 'getCategoriesCovenants']);

/**
 * Type Categories of Convenants
 */

Route::get('/covenants-type', [TypeCategoryConvenantController::class, 'index']);
Route::post('/convenants-type/store', [TypeCategoryConvenantController::class, 'store']);
Route::get('/convenants-type/getAgreement/{id}', [TypeCategoryConvenantController::class, 'getAgreement']);

/**
 * Banks Route
 */

Route::get('/banks', [BanksController::class, 'index']);
Route::post('/banks/store', [BanksController::class, 'store']);
Route::get('/banks/load/{id}', [BanksController::class, 'getItem']);


Route::get('/AccountType', [AccountTypeController::class, 'index']);
Route::post('/AccountType/store', [AccountTypeController::class, 'store']);
Route::get('/AccountType/load/{id}', [AccountTypeController::class, 'getItem']);

Route::get('/BankAccount', [BankAccountController::class, 'index']);
Route::post('/BankAccount/store', [BankAccountController::class, 'store']);
Route::get('/BankAccount/load/{id}', [BankAccountController::class, 'getItem']);


Route::get('/Cashflow', [CashflowController::class, 'index']);
Route::post('/Cashflow/store', [CashflowController::class, 'store']);
Route::get('/Cashflow/load/{id}', [CashflowController::class, 'getItem']);


Route::get('/report/associate', [ReportsController::class, 'associate']);
Route::get('/report/allAssociate', [ReportsController::class, 'allAssociate']);
Route::get('/report/agreement', [ReportsController::class, 'agreement']);
Route::get('/report/covenant', [ReportsController::class, 'covenant']);
Route::get('/report/cashflow', [ReportsController::class, 'cashflow']);
Route::get('/report/reference', [ReportsController::class, 'reference']);
Route::post('/aReport', [ReportsController::class, 'aReport']);


Route::get('/migracao', [MigracaoController::class, 'index']);
Route::get('/enderecos', [MigracaoController::class, 'atualiza_endereco']);


Route::get('/aAlerts', [AlertController::class, 'aAlerts']);
Route::get('/aVerAlerta', [AlertController::class, 'aVerAlerta']);
Route::get ('/aVisualizado', [AlertController::class, 'aVisualizado']);
Route::get ('/meus-alertas', [AlertController::class, 'meusAlertas']);
Route::get('/aUsersAlert', [AlertController::class, 'aUsersAlert']);
Route::get('/aQuemViu', [AlertController::class, 'aQuemViu']);
Route::get ('/alertas', [AlertController::class, 'index']);
Route::post('/alertas/store', [AlertController::class, 'store']);

