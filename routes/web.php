<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillingController;

Route::get('/', [BillingController::class, 'index'])->name('bill.index');
Route::get('/index', [BillingController::class, 'index'])->name('bill.index');

Route::post('/bill/save', [BillingController::class, 'saveBill'])->name('bill.store');

Route::get('/bill-view/{bill_id}', [BillingController::class, 'viewBill'])->name('bill.view');
Route::get('/bill-delete/{bill_id}', [BillingController::class, 'deleteBill'])->name('bill.delete');
