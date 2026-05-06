<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::post('/callbacks/{provider}', [PaymentController::class, 'callback'])->name('payments.callback');
