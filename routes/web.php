<?php

use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\Admin\DisbursementAdminController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return redirect()->route('disbursementadmin.index');
});

// Standard CRUD
Route::resource('disbursements', DisbursementController::class);

// Admin 
Route::resource('disbursementadmin', DisbursementAdminController::class);

//

Route::patch('/disbursements/{id}/approve', [DisbusrsementController::class, 'approve'])->name('disbursements.approve');

