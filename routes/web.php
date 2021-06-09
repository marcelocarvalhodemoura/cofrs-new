<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use \App\Http\Controllers\AssociateController;
use \App\Http\Controllers\TypeAssociateController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConvenantController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('', function() {
    // $category_name = 'auth';
    $data = [
        'category_name' => 'auth',
        'page_name' => 'auth_boxed',
        'has_scrollspy' => 0,
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    // $pageName = 'auth_default';
    return view('pages.authentication.auth_login_boxed')->with($data);
})->name('login');

Route::post('/auth', [UserController::class, 'authentication']);

Route::get('/dashboard', [DashboardController::class, 'index']);




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
Route::get('/associates',[AssociateController::class, 'index']);
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
Route::get('/covenants',[ConvenantController::class, 'index']);
Route::get('/covenants/associate/list', [ConvenantController::class, 'getAssociates']);

