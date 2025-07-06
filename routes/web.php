<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CpmkCplController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Reports\MahasiswaController;
use App\Http\Controllers\Reports\MatakuliahSemesterController;
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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    // Import Routes
    Route::get('/import', [ImportController::class, 'showImportForm'])->name('import.form');
    Route::get('/import/preview', [ImportController::class, 'previewImport'])->name('import.preview');
    Route::post('/import/preview', [ImportController::class, 'previewImport']);
    Route::post('/import/process', [ImportController::class, 'processImport'])->name('import.process');
    Route::get('/import/cancel', [ImportController::class, 'cancelImport'])->name('import.cancel');

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::resource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
        Route::resource('matakuliah-semester', MatakuliahSemesterController::class)->only(['index', 'show']);
    });

    // Import Logs Routes - only index and show (no delete functionality)
    Route::resource('import-logs', App\Http\Controllers\ImportLogController::class)->only(['index', 'show']);

    Route::get('/report/cpmk-cpl', [CpmkCplController::class, 'index'])->name('report.cpmk-cpl');

    /*
     * Siklus Routes
     */
    // Replace the resource route with explicit route definitions
    // Route::resource('siklus', \App\Http\Controllers\SiklusController::class);
    Route::get('siklus', [\App\Http\Controllers\SiklusController::class, 'index'])->name('siklus.index');
    Route::get('siklus/create', [\App\Http\Controllers\SiklusController::class, 'create'])->name('siklus.create');
    Route::post('siklus', [\App\Http\Controllers\SiklusController::class, 'store'])->name('siklus.store');
    Route::get('siklus/{siklus}', [\App\Http\Controllers\SiklusController::class, 'show'])->name('siklus.show');
    Route::get('siklus/{siklus}/edit', [\App\Http\Controllers\SiklusController::class, 'edit'])->name('siklus.edit');
    Route::put('siklus/{siklus}', [\App\Http\Controllers\SiklusController::class, 'update'])->name('siklus.update');
    Route::delete('siklus/{siklus}', [\App\Http\Controllers\SiklusController::class, 'destroy'])->name('siklus.destroy');

    Route::get('siklus/{siklus}/configure', [\App\Http\Controllers\SiklusController::class, 'configure'])->name('siklus.configure');
    Route::post('siklus/{siklus}/save-cpl-selections', [\App\Http\Controllers\SiklusController::class, 'saveCplSelections'])->name('siklus.save-cpl-selections');

    /*
     * Siklus 2 Routes
     */
    Route::get('siklus2', [\App\Http\Controllers\Siklus2Controller::class, 'index'])->name('siklus2.index');
    Route::get('siklus2/create', [\App\Http\Controllers\Siklus2Controller::class, 'create'])->name('siklus2.create');
    Route::post('siklus2', [\App\Http\Controllers\Siklus2Controller::class, 'store'])->name('siklus2.store');
    Route::get('siklus2/{siklus}', [\App\Http\Controllers\Siklus2Controller::class, 'show'])->name('siklus2.show');
    Route::get('siklus2/{siklus}/edit', [\App\Http\Controllers\Siklus2Controller::class, 'edit'])->name('siklus2.edit');
    Route::put('siklus2/{siklus}', [\App\Http\Controllers\Siklus2Controller::class, 'update'])->name('siklus2.update');
    Route::delete('siklus2/{siklus}', [\App\Http\Controllers\Siklus2Controller::class, 'destroy'])->name('siklus2.destroy');

    Route::get('siklus2/{siklus}/configure', [\App\Http\Controllers\Siklus2Controller::class, 'configure'])->name('siklus2.configure');
    Route::post('siklus2/{siklus}/save-pi-selections', [\App\Http\Controllers\Siklus2Controller::class, 'savePiSelections'])->name('siklus2.save-pi-selections');

    // Master Mata Kuliah Semester - Changed from nested resource to simple resource with prefix and name
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('matakuliah-semester', \App\Http\Controllers\MataKuliahSemesterController::class);
    });
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
