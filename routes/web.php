<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

Route::get('/', [Controller::class, 'ifLoged'])->name('home');
Route::get('/all', [Controller::class,'ifLoged'])->name('all');
Route::get('/costs', [Controller::class,'ifLoged'])->name('costs');
Route::get('/search', [Controller::class,'ifLoged'])->name('search');
Route::get('/vacation', [Controller::class,'ifLoged'])->name('vacation');
Route::get('/users', [Controller::class,'ifLoged'])->name('users');

Route::get('/users/add', [Controller::class,'ifLoged'])->name('add');

Route::get('/users/register', [Controller::class,'ifLoged'])->name('register');

Route::post('/createUser', [Controller::class, 'storeUser'])->name('users.createUser');

Route::delete('/users/{id}', [Controller::class, 'destroy'])->name('users.destroy');

Route::get('/login', function(){
    return view('login.login');
})->name('login');

Route::post('/login', [Controller::class, 'loginUser'])->name('login.login');

Route::post('/logout', [Controller::class, 'logout'])->name('logout');
