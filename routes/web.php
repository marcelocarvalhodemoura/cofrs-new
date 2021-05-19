<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
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
});

Route::get('/dashboard', function(){
    $data = [
        'category_name' => 'dashboard',
        'page_name' => 'analytics',
        'has_scrollspy' => 0,
        'offsetTop' => '',
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    return view('dashboard')->with($data);
});

Route::get('/logout', function(){
    return redirect('');
})->name('logout');

/**
 * User routes
 */
Route::get('/users', [UserController::class, 'index']);

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
Route::get('/associates',function(){
    $data = [
        'category_name' => 'associates',
        'page_name' => 'associates',
        'has_scrollspy' => 0,
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    return view('associate.list')->with($data);
});

Route::get('/covenants',function(){
    $data = [
        'category_name' => 'covenants',
        'page_name' => 'covenants',
        'has_scrollspy' => 0,
        'scrollspy_offset' => '',
        'alt_menu' => 0,
    ];
    return view('covenants.list')->with($data);
});

