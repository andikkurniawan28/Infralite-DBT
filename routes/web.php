<?php

use App\Http\Controllers\BackupFileController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BackupLogController;
use App\Http\Controllers\ManualBackupController;
use App\Http\Controllers\ScheduledBackupController;
use App\Http\Controllers\ConnectingDatabaseController;
use App\Http\Controllers\DatabaseConnectionController;

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

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'process'])->name('login_process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', WelcomeController::class)->name('welcome')->middleware(['auth']);
Route::resource('/user', UserController::class)->middleware(['auth']);
Route::resource('/database_connection', DatabaseConnectionController::class)->middleware(['auth']);
Route::get('/connecting_database/{database_connection_id}', ConnectingDatabaseController::class)->name('connecting_database');
Route::get('/manual_backup', [ManualBackupController::class, 'index'])->name('manual_backup.index')->middleware(['auth']);
Route::post('/manual_backup', [ManualBackupController::class, 'process'])->name('manual_backup.process')->middleware(['auth']);
Route::get('/backup_log', BackupLogController::class)->name('backup_log.index')->middleware(['auth']);
Route::resource('/schedule', ScheduleController::class)->middleware(['auth']);
Route::get('/backup_file', [BackupFileController::class, 'index'])->name('backup_file.index')->middleware(['auth']);
Route::get('/backup_file/data', [BackupFileController::class, 'data'])->name('backup_file.data');
Route::post('/backup_file/bulk_delete', [BackupFileController::class, 'bulkDelete'])->name('backup_file.bulk_delete')->middleware(['auth']);
Route::delete('/backup_file/delete_all', [BackupFileController::class, 'deleteAll'])->name('backup_file.delete_all')->middleware(['auth']);
Route::get('/scheduled_backup', [ScheduledBackupController::class, 'process'])->name('scheduled_backup.process');
Route::get('/scheduled_backup/download/{filename}', [ScheduledBackupController::class, 'download'])->name('scheduled_backup.download');

