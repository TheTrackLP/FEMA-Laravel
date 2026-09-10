<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::middleware('auth')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dash');
    });

    Route::controller(BorrowersController::class)->group(function(){
        Route::get('/admin/borrowers', 'BorrowerDashboard')->name('borrow.dash');
        Route::post('/admin/borrowers/store', 'BorrowerStore')->name('borrow.store');
        Route::post('/admin/borrowers/store/{id}', 'BorrowerUpdate')->name('borrow.update');
        Route::post('/admin/borrowers/status/{id}', 'BorrowerStatus')->name('borrow.status');
    });

    Route::controller(DepartmentController::class)->group(function(){
        Route::get('/admin/departments', 'DepartmentDashboard')->name('dept.dash');
        Route::post('/admin/departments/store', 'DepartmentStore')->name('dept.store');
        Route::post('/admin/departments/edit/{id}', 'DepartmentEdit')->name('dept.edit');
    });
});

require __DIR__.'/auth.php';
