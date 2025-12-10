<?php

use App\Http\Controllers\Admin\DisbursementAdminController;

use App\Models\Disbursement;
use App\Models\Payee;
use App\Models\FundSource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::resource('disbursementadmin', DisbursementAdminController::class);

Route::get('/', function(){
    return redirect()->route('disbursementadmin.index');
});

Route::get('/disbursements/{id}', function ($id) {
    
    // Fetch the record with all relationships so the view can display them
    $disbursement = Disbursement::with(['payee', 'fundSource', 'items', 'deductions'])
                        ->findOrFail($id);

    return view('disbursements.details', compact('disbursement'));

})->name('disbursements.details');