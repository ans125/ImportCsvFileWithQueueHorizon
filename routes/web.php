<?php

use App\Http\Controllers\ProductImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('product-import',[ProductImportController::class,'index'])->name('products.import.index');

Route::post('product-import',[ProductImportController::class,'store'])->name('products.import.store');