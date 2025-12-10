<?php

use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\FundAccountController;
use App\Http\Controllers\PayeeAccountController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Standard CRUD
Route::apiResource('disbursements', DisbursementController::class);
Route::apiResource('payees', PayeeAccountController::class);
Route::apiResource('funds', FundAccountController::class);

// Approve routes
Route::patch('/disbursements/{id}/approve', [DisbusrsementController::class, 'approve'])->name('disbursements.approve');

// Deactivate routes
Route::patch('/payees/{id}deactivate', [PayeeAccountController::class, 'deactivate']);

