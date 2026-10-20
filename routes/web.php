<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\LoanTypesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReportsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware('auth')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dash');
        Route::get('/admin/logout', 'AdminLogout')->name('admin.logout');
    });

    Route::controller(BorrowersController::class)->group(function(){
        Route::get('/admin/borrowers', 'BorrowerDashboard')->name('borrow.dash');
        Route::post('/admin/borrowers/store', 'BorrowerStore')->name('borrow.store');
        Route::post('/admin/borrowers/store/{id}', 'BorrowerUpdate')->name('borrow.update');
        Route::post('/admin/borrowers/status/{id}', 'BorrowerStatus')->name('borrow.status');
    });

    Route::controller(LoansController::class)->group(function(){
        Route::get('/admin/loans', 'LoansDashboard')->name('loans.dash');
        Route::post('/admin/loans/store', 'LoansAppliStore')->name('loans.store');
        Route::post('/admin/loans/update/{id}', 'LoansAppliUpdate')->name('loans.update');
        Route::get('/admin/loans/{id}/next-payment-schedule', 'NextPaymentDetails');
    });

    Route::controller(PaymentsController::class)->group(function(){
        Route::get('/admin/payments', 'PaymentsDashboard')->name('pays.dash');
        Route::post('/admin/payments/store', 'PaymentsStore')->name('pays.store');
        Route::get('/admin/loans/{loan}/current-schedule', 'currentSchedule');
    });

    Route::controller(DepartmentController::class)->group(function(){
        Route::get('/admin/departments', 'DepartmentDashboard')->name('dept.dash');
        Route::post('/admin/departments/store', 'DepartmentStore')->name('dept.store');
        Route::post('/admin/departments/edit/{id}', 'DepartmentEdit')->name('dept.edit');
    });

    Route::controller(LoanTypesController::class)->group(function(){
        Route::get('/admin/loan-type', 'LoanTypesDashboard')->name('types.dash');
        Route::post('/admin/loan-type/store', 'LoanTypesStore')->name('types.store');
        Route::post('/admin/loan-type/update/{id}', 'LoanTypesUpdate')->name('types.update');
        Route::post('/admin/loan-type/status/{id}', 'LoanTypesStatus')->name('type.stat');
    });

    Route::controller(AccountsController::class)->group(function(){
        Route::get('/admin/accounts', 'AccountsDashboard')->name('accts.dash');
        Route::post('/admin/accounts/create', 'AccountsCreation')->name('accts.create');
        Route::post('/admin/accounts/update/{id}', 'AccountsUpdate')->name('accts.update');
    });

    Route::controller(ReportsController::class)->group(function(){
        Route::get('/admin/reports', 'ReportsDashboard')->name('report.dash');
    });
});

require __DIR__.'/auth.php';
