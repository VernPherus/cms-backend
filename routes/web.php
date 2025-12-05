<?php

use App\Http\Controllers\DisbursementController;
use Illuminate\Support\Facades\Route;

// Standard CRUD
Route::resource('disbursements', DisbursementController::class);

//
Route::patch('/disbursements/{id}/approve', [DisbusrsementController::class, 'approve'])->name('disbursements.approve');

