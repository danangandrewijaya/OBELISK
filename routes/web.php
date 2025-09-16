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

    // Import CPL Routes
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [ImportController::class, 'showImportForm'])->name('form');
        Route::get('/preview', [ImportController::class, 'previewImport'])->name('preview');
        Route::post('/preview', [ImportController::class, 'previewImport']);
        Route::post('/process', [ImportController::class, 'processImport'])->name('process');
        Route::get('/cancel', [ImportController::class, 'cancelImport'])->name('cancel');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::resource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
        Route::resource('matakuliah-semester', MatakuliahSemesterController::class)->only(['index', 'show']);
    });

    Route::put('/report/mahasiswa/{mahasiswa}/update-kurikulum', [App\Http\Controllers\Reports\MahasiswaController::class, 'updateKurikulum'])->name('report.mahasiswa.update_kurikulum');
    Route::delete('/report/nilai/{nilai}', [App\Http\Controllers\Reports\NilaiController::class, 'destroy'])->name('report.nilai.destroy');

    // Import Logs Routes - only index and show (no delete functionality)
    Route::resource('import-logs', App\Http\Controllers\ImportLogController::class)->only(['index', 'show']);

    Route::get('/report/cpmk-cpl', [CpmkCplController::class, 'index'])->name('report.cpmk-cpl');

    /*
     * Siklus Routes
     */
    Route::get('siklus', [\App\Http\Controllers\SiklusController::class, 'index'])->name('siklus.index');
    Route::get('siklus/create', [\App\Http\Controllers\SiklusController::class, 'create'])->name('siklus.create');
    Route::post('siklus', [\App\Http\Controllers\SiklusController::class, 'store'])->name('siklus.store');
    Route::get('siklus/compare-cpl', [\App\Http\Controllers\SiklusController::class, 'compareCPL'])->name('siklus.compare-cpl');
    Route::get('siklus/compare-cpl/data', [\App\Http\Controllers\SiklusController::class, 'getCompareCPLData'])->name('siklus.compare-cpl.data');
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

    // Master Kurikulum CRUD
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('kurikulum', \App\Http\Controllers\Master\KurikulumController::class);
        Route::resource('matakuliah-semester', \App\Http\Controllers\MataKuliahSemesterController::class);
    });

    // Standalone Mata Kuliah Semester import (separate from ImportController)
    Route::get('/matkul-smt/import', [\App\Http\Controllers\MataKuliahSemesterImportController::class, 'showForm'])->name('matkul-smt.import.form');
    Route::post('/matkul-smt/import/preview', [\App\Http\Controllers\MataKuliahSemesterImportController::class, 'preview'])->name('matkul-smt.import.preview');
    Route::post('/matkul-smt/import/process', [\App\Http\Controllers\MataKuliahSemesterImportController::class, 'process'])->name('matkul-smt.import.process');
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
