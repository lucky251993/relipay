<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/add-detail-form', function () {
    return view('addDetailForm');
   })->name('addDetailForm');

Route::post('/save-details', [UserController::class, 'store'])->name('saveDetails');

Route::post('/details/upload', [UserController::class, 'bulkUpload'])->name('details.bulkUpload');
Route::get('/details/download', [UserController::class, 'bulkDownload'])->name('details.bulkDownload');