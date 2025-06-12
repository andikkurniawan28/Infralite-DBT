<?php

use App\Http\Controllers\ConnectingDatabaseController;
use App\Http\Controllers\DatabaseConnectionController;
use App\Http\Controllers\ManualBackupController;
use App\Http\Controllers\WelcomeController;
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

Route::get('/', WelcomeController::class)->name('welcome');
Route::resource('/database_connection', DatabaseConnectionController::class);
Route::get('/connecting_database/{database_connection_id}', ConnectingDatabaseController::class)->name('connecting_database');
Route::get('/manual_backup', [ManualBackupController::class, 'index'])->name('manual_backup.index');
Route::post('/manual_backup', [ManualBackupController::class, 'process'])->name('manual_backup.process');
