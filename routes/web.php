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
use App\Http\Controllers\RegisteredBorrowerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::controller(RegisteredBorrowerController::class)->group(function(){
    Route::get('/register', 'RegisterPage')->name('register');
    Route::post('/register/borrower/store', 'RegisterBorrower')->name('store.borrower');
});


Route::middleware(['auth', 'roles:admin'])->group(function() {
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dash');
    });

    Route::controller(BorrowersController::class)->group(function(){
        Route::get('/admin/borrowers', 'BorrowerDashboard')->name('borrow.list');
        Route::get('/admin/borrower/show/{id}', 'BorrowerDetails');
        Route::post('/admin/borrower/update', 'BorrowerUpdate')->name('borrow.update');
        Route::get('/admin/borrower/delete/{id}', 'BorrowerDelete')->name('borrow.delete');
    });

    Route::controller(LoansController::class)->group(function(){
        Route::get('/admin/loan-lists', 'LoansLists')->name('loans.dash');
        Route::get('/admin/loan-lists/getData/{id}', 'GetBorrower');
        Route::post('/admin/loan-lists/applicant/store', 'AddApplication')->name('loans.store');
        Route::get('/admin/loan-lists/applicant/edit/{id}', 'loanListsEdit');
        Route::post('/admin/loan-lists/applicant/update', 'loanListsUpdate')->name('loans.update');
    });

    
    Route::controller(PaymentsController::class)->group(function(){
        Route::get('/admin/payments', 'PaymentLists')->name('pay.list');
    });

    Route::controller(LoanPlansController::class)->group(function(){
       Route::get('/admin/loan-plans', 'LoanPlanLists')->name('plan.list'); 
       Route::post('/admin/loan-plans/add', 'AddLoanPlan')->name('plan.add');
       Route::get('/admin/loan-plans/edit/{id}', 'LoanPlanEdit');
       Route::post('/admin/loan-plans/update', 'LoanPlanUpdate')->name('plan.update');
       Route::get('/admin/loan-plans/delete/{id}', 'LoanPlanDelete')->name('plan.delete');
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('/admin/reports', 'ReportLoan')->name('report');
    });

    Route::controller(DepartmentController::class)->group(function(){
        Route::get('/admin/departments', 'DepartmentsLists')->name('dept.list');
        Route::post('/admin/departments/store', 'DepartmentStore')->name('dept.add');
        Route::get('/admin/departments/edit/{id}', 'DepartmentEdit');
        Route::post('/admin/departments/update', 'DepartmentUpdate')->name('dept.update');
        Route::get('/admin/departments/delete/{id}', 'DepartmentDelete')->name('dept.delete');
    });

    Route::controller(AccountsController::class)->group(function(){
        Route::get('/admin/accounts', 'AllACcounts')->name('accts');
    });
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