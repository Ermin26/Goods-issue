<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\CostsController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Models\Vacation;

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
    Route::get('/admin', [AdminController::class, 'dbs'])->name('admin');
    Route::post('/getDb', [AdminController::class, 'getDb'])->name('getDb');

    Route::get('/', [BillsController::class, 'getNumbersPerMonthAndPerYear'])->name('home');
    Route::get('/importVacations', [VacationController::class, 'importVacations'])->name('importVacations');
    Route::get('/importEmployee', [EmployeeController::class, 'importEmployee'])->name('importEmployee');
    Route::get('/importCosts', [CostsController::class, 'importCosts'])->name('importCosts');
    Route::get('/importBills', [BillsController::class, 'importBills'])->name('importBills');
    #Route::get('/allDbs', [VacationController::class, 'allDbs'])->name('allDbs');

    Route::prefix('all')->group(function(){
        Route::get('/',[BillsController::class, 'testAll'])->name('all');
        Route::get('/payed',[BillsController::class, 'testAll'])->name('payed');
        Route::get('/notpayed',[BillsController::class, 'testAll'])->name('notpayed');
        Route::get('/view/{id}',[BillsController::class, 'findBill'])->name('viewBill');
        Route::get('/edit/{id}',[BillsController::class, 'editBill'])->name('editBill');
        Route::put('/update/bill/{id}',[BillsController::class, 'updateBill'])->name('updateBill');
        Route::post('/update/products/{id}',[BillsController::class, 'updateProducts'])->name('updateProducts');
        Route::delete('/delete/{id}', [BillsController::class, 'deleteUserBill'])->name('deleteUserBill');
    });

    Route::get('/costs',[CostsController::class, 'allCosts'])->name('costs');
    Route::post('/addCosts',[CostsController::class, 'addCosts'])->name('addCosts');
    Route::delete('/costs/{id}',[CostsController::class, 'deleteBill'])->name('costs.deleteBill');

    Route::prefix('/search')->group(function(){
        Route::get('/',[BillsController::class,'searchUsers'])->name('search');
        Route::post('/user',[BillsController::class, 'searchUser'])->name('searchUser');
    });


    Route::prefix('vacation')->group(function(){
        Route::get('/',[VacationController::class, 'vacationData'])->name('vacation');
        Route::post('/updateVacations', [VacationController::class, 'updateVacations'])->name('updateVacation');
        Route::post('/approveHoliday/{id}', [VacationController::class, 'approveHoliday'])->name('approveHoliday');
        Route::post('/rejectHoliday/{id}', [VacationController::class, 'rejectHoliday'])->name('rejectHoliday');
        Route::post('/userUsedHolidays', [VacationController::class, 'userUsedHolidays'])->name('userUsedHolidays');
        Route::post('/sendMsg',[VacationController::class, 'sendMsg'])->name('sendMsg');
    });
    Route::prefix('users')->group(function(){
        Route::get('/',[UsersController::class, 'findAllUsers'])->name('users');
        #Route::get('/',[EmployeeController::class, 'importEmployee'])->name('users');
        Route::get('/add',[EmployeeController::class, 'checkEmails'])->name('users.add');
        Route::get('/edit/{id}',[UsersController::class, 'findUser'])->name('users.editUser');
        Route::get('/employee/{id}',[EmployeeController::class, 'findEmployee'])->name('users.editEmployee');
        Route::post('/update/{id}',[UsersController::class, 'updateUser'])->name('users.update');
        Route::post('/employeeupdate/{id}',[EmployeeController::class, 'updateEmployee'])->name('users.updateEmployee');
        Route::post('/addEmployee',[EmployeeController::class, 'addEmployee'])->name('users.addEmployee');
        Route::get('/register',[UsersController::class, 'checkUsers'])->name('users.register');

        Route::delete('/{id}', [UsersController::class, 'deleteUser'])->name('users.destroy');
        Route::delete('/employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->name('users.deleteEmployee');
    });
    Route::post('/newBill', [BillsController::class, 'newBill'])->name('create.newBill');
    Route::post('/createUser', [UsersController::class, 'storeUser'])->name('users.createUser');

});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth.employee'])->group(function(){
    Route::prefix('employee')->group(function(){
        Route::get('/', [EmployeeController::class,'employeeData'])->name('employeeHome');
        Route::get('/vacation', [EmployeeController::class,'vacation'])->name('employeeVacation');
        Route::get('/profile', [EmployeeController::class,'getProfile'])->name('profile');
        Route::get('/editHoliday/{id}', [EmployeeController::class,'editHoliday'])->name('editHoliday');
        Route::post('/vacation',[EmployeeController::class, 'newHoliday'])->name('newHoliday');
        Route::post('/editProfile/{id}',[EmployeeController::class, 'updateProfile'])->name('editProfile');
        Route::post('/updatedHoliday/{id}',[EmployeeController::class, 'updateHoliday'])->name('updateHoliday');
        Route::delete('/deleteHoliday/{id}',[EmployeeController::class, 'deleteHoliday'])->name('deleteHoliday');
        Route::post('/myHolidays/{id}', [EmployeeController::class, 'myHolidays'])->name('myHolidays');
        Route::Post('/studentSendEmail/{id}', [EmployeeController::class, 'studentSendEmail'])->name('studentSendEmail');
    });
});


Route::fallback(function () {
    return view('error404');
});
