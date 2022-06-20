<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('files')->name('files.')->group(function(){
    Route::get('create',[FileController::class,'create'])->name('create');
    Route::post('store',[FileController::class,'store'])->name('store');
    Route::get('',[FileController::class,'index'])->name('index');
    Route::get('{file}',[FileController::class,'show'])->name('show');
    Route::delete('{file}',[FileController::class,'destroy'])->name('destroy');

});
