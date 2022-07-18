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

Route::post('/auth', [UserController::class, 'authentication']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');




/**
 * User routes
 */
Route::get('/users', [UserController::class, 'index']);
Route::get('/logout', [UserController::class, 'logOut'])->name('logout');

//load form with data
Route::get('/users/load/{id}', [UserController::class, 'getUser']);

//Remove data
Route::post('/users/delete/{id}', [UserController::class, 'delete']);

//Forgot password
Route::post('/users/pass/{id}', [UserController::class, 'forgotPassword']);

//Send data from the form
Route::post('/users/store', [UserController::class, 'store']);


/**
 * Associates Routes
 */
Route::get('/associates', [AssociateController::class, 'index']);
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
Route::get('/report/agreement', [ReportsController::class, 'agreement']);
Route::get('/report/covenant', [ReportsController::class, 'covenant']);
Route::get('/report/cashflow', [ReportsController::class, 'cashflow']);
Route::post('/aReport', [ReportsController::class, 'aReport']);


Route::get('/migracao', [MigracaoController::class, 'index']);

