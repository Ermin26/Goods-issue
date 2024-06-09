<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MongoDBController;
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

Route::get('/login', function(){
    return view('login.login');
})->name('login');

Route::post('/login', [LoginController::class, 'loginUser'])->name('login.login');

Route::middleware(['auth'])->group(function(){
    Route::get('/', function(){
        return view('index');
    })->name('home');

    Route::get('/all',[LoginController::class, 'ifLoged'])->name('all');
    Route::get('/costs',[LoginController::class, 'ifLoged'])->name('costs');
    Route::get('/search',[LoginController::class, 'ifLoged'])->name('search');
    Route::get('/vacation',[LoginController::class, 'ifLoged'])->name('vacation');
    
    Route::prefix('users')->group(function(){
        Route::get('/',[LoginController::class, 'ifLoged'])->name('users');

        Route::get('/add',[LoginController::class, 'ifLoged'])->name('users.add');

        Route::get('/register',[MongoDBController::class, 'selectUsers'])->name('users.register');

        Route::delete('/{id}', [Controller::class, 'destroy'])->name('users.destroy');
    });
    
    Route::post('/createUser', [Controller::class, 'storeUser'])->name('users.createUser');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});