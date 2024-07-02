<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\CostsController;
use App\Http\Controllers\CheckRoleController;
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
    Route::get('/', [BillsController::class, 'getNumbersPerMonthAndPerYear'])->name('home');

    Route::prefix('all')->group(function(){
        Route::get('/',[BillsController::class, 'testAll'])->name('all');
        Route::get('/view/{id}',[BillsController::class, 'findBill'])->name('viewBill');
        Route::get('/edit/{id}',[BillsController::class, 'editBill'])->name('editBill');

    });

    Route::get('/costs',[CostsController::class, 'allCosts'])->name('costs');
    Route::post('/addCosts',[CostsController::class, 'addCosts'])->name('addCosts');
    Route::delete('/costs/{id}',[CostsController::class, 'deleteBill'])->name('costs.deleteBill');

    Route::prefix('/search')->group(function(){
        Route::get('/',function(){
            return view('users.search');
            })->name('search');
        Route::post('/user',[BillsController::class, 'searchUser'])->name('searchUser');
    });


    Route::get('/vacation',function(){
        return view('info');
    })->name('vacation');

    Route::prefix('users')->group(function(){
        Route::get('/',[UsersController::class, 'findAllUsers'])->name('users');
        Route::get('/add',[UsersController::class, 'checkEmails'])->name('users.add');
        Route::get('/edit/{id}',[UsersController::class, 'findUser'])->name('users.editUser');
        Route::get('/employee/{id}',[UsersController::class, 'findEmployee'])->name('users.editEmployee');
        Route::post('/update/{id}',[UsersController::class, 'updateUser'])->name('users.update');
        Route::post('/employeeupdate/{id}',[UsersController::class, 'updateEmployee'])->name('users.updateEmployee');
        Route::post('/addEmployee',[UsersController::class, 'addEmployee'])->name('users.addEmployee');
        Route::get('/register',[UsersController::class, 'checkUsers'])->name('users.register');

        Route::delete('/{id}', [UsersController::class, 'deleteUser'])->name('users.destroy');
        Route::delete('/employee/delete/{id}', [UsersController::class, 'deleteEmployee'])->name('users.deleteEmployee');
    });
    Route::post('/newBill', [BillsController::class, 'newBill'])->name('create.newBill');
    Route::post('/createUser', [UsersController::class, 'storeUser'])->name('users.createUser');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});