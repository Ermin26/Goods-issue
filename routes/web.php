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

Route::get('/', function () {
    return view('index');
})->name('home');
Route::get('/all', function () {
    return view('bills.selled');
});
Route::get('/costs', function () {
    return view('bills.costs');
});
Route::get('/search', function () {
    return view('users.search');
});
Route::get('/vacation', function () {
    return view('holidays.holidays');
});
Route::get('/users', function () {
    return view('users.allUsers');
});

Route::get('/add', function () {
    return view('users.add');
})->name('add');

Route::get('/register', function () {
    return view('users.register');
})->name('register');

Route::post('/createUser', [Controller::class, 'registerUser'])->name('createUser');

Route::get('/login', function () {
    return view('login.login');
});
Route::get('/logout', function () {
    return view('index');
});
