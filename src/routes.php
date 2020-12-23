<?php

use Illuminate\Support\Facades\Route;
use \Simplepay\SimplepayApi\HTTP\Controllers\SandboxController;

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

Route::prefix('simplepay_sandbox')->group(function() {
    Route::get('/',[ SandboxController::class,'show'])->name('simplepay_sandbox');
    Route::get('start-test',[SandboxController::class,'start_test'])->name('simplepay_sandbox.start_test');
    Route::get('back',[SandboxController::class,'back'])->name('simplepay_sandbox.back');
    Route::get('query',[SandboxController::class,'query'])->name('simplepay_sandbox.query');
    Route::get('refund',[SandboxController::class,'refund'])->name('simplepay_sandbox.refund');
});
