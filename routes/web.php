<?php

use App\Http\Controllers\Admin\DisbursementAdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::resource('disbursementadmin', DisbursementAdminController::class);

Route::get('/', function(){
    return redirect()->route('disbursementadmin.index');
});

