<?php

use App\Http\Controllers\ProductImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('product-import', [ProductImportController::class, 'index'])->name('products.import.index');
Route::post('product-import', [ProductImportController::class, 'store'])->name('products.import.store');
Route::get('batch/{batchId}/progress', [ProductImportController::class, 'batchProgress'])->name('batch.progress');
