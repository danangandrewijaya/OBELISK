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

    Route::get('/import', [ImportController::class, 'showImportForm'])->name('import.form');
    Route::post('/import', [ImportController::class, 'importExcel'])->name('import.excel');

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::resource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
        Route::resource('matakuliah-semester', MatakuliahSemesterController::class)->only(['index', 'show']);
    });

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
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
