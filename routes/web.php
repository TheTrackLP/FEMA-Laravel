<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\LoanPlansController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AccountsController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'roles:admin'])->group(function() {
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dash');
    });

    Route::controller(BorrowersController::class)->group(function(){
        Route::get('/admin/borrowers', 'BorrowerDashboard')->name('borrow.dash');
    });

    Route::controller(LoansController::class)->group(function(){
        Route::get('/admin/loans', 'LoansLists')->name('loans.dash');
    });

    
    Route::controller(PaymentsController::class)->group(function(){
        Route::get('/admin/payments', 'PaymentLists')->name('pay.list');
    });

    Route::controller(LoanPlansController::class)->group(function(){
       Route::get('/admin/loan_plans', 'LoanPlanLists')->name('plan.list'); 
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('/admin/reports', 'ReportLoan')->name('report');
    });

    Route::controller(DepartmentController::class)->group(function(){
        Route::get('/admin/departments', 'DepartmentsLists')->name('dept');
    });

    Route::controller(AccountsController::class)->group(function(){
        Route::get('/admin/accounts', 'AllACcounts')->name('accts');
    });
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';