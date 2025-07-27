<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LongFormController;

Route::get('/', function () {
    return view('long-form');
});

Route::get('/patient-form', [LongFormController::class, 'create'])->name('patient.form');

Route::post('/patient-form', [LongFormController::class, 'store'])->name('patient.store');