<?php

use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\ApprovalController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\HasilUjiController;
use App\Http\Controllers\Api\V1\JadwalSamplingController;
use App\Http\Controllers\Api\V1\LaporanController;
use App\Http\Controllers\Api\V1\MobileController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ParameterUjiController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PermohonanController;
use App\Http\Controllers\Api\V1\QcController;
use App\Http\Controllers\Api\V1\RegistrasiSampleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — SIM Labkesda Purwakarta
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api/v1 (configured in bootstrap/app.php).
| Public routes: auth/login only.
| All other routes require JWT authentication.
|
*/

// ─── Public Routes ──────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('auth.login');

// ─── Authenticated Routes ───────────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {

    // ── Auth ─────────────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::get('/me', [AuthController::class, 'me'])->name('me');
    });

    // ── Users ─────────────────────────────────────────────────────────────
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/petugas-lapangan', [UserController::class, 'petugasLapangan'])
            ->name('petugas-lapangan');
    });

    // ── Permohonan Pengujian ─────────────────────────────────────────────
    Route::prefix('permohonan')->name('permohonan.')->group(function () {
        Route::get('/', [PermohonanController::class, 'index'])
            ->middleware('permission:permohonan.view')
            ->name('index');
        Route::post('/', [PermohonanController::class, 'store'])
            ->middleware('permission:permohonan.create')
            ->name('store');
        Route::get('/{id}', [PermohonanController::class, 'show'])
            ->middleware('permission:permohonan.view')
            ->name('show');
        Route::put('/{id}', [PermohonanController::class, 'update'])
            ->middleware('permission:permohonan.update')
            ->name('update');
        Route::delete('/{id}', [PermohonanController::class, 'destroy'])
            ->middleware('permission:permohonan.delete')
            ->name('destroy');
        Route::post('/{id}/verify', [PermohonanController::class, 'verify'])
            ->middleware('permission:permohonan.update')
            ->name('verify');
    });

    // ── Payments ─────────────────────────────────────────────────────────
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])
            ->middleware('permission:payment.view')
            ->name('index');
        Route::post('/', [PaymentController::class, 'store'])
            ->middleware('permission:payment.create')
            ->name('store');
    });

    // ── Jadwal Sampling ──────────────────────────────────────────────────
    Route::prefix('jadwal-sampling')->name('jadwal-sampling.')->group(function () {
        Route::get('/', [JadwalSamplingController::class, 'index'])
            ->middleware('permission:jadwal.view')
            ->name('index');
        Route::post('/', [JadwalSamplingController::class, 'store'])
            ->middleware('permission:jadwal.create')
            ->name('store');
    });

    // ── Mobile (Petugas Lapangan) ────────────────────────────────────────
    Route::prefix('mobile')->name('mobile.')->middleware('role:petugas_lapangan')->group(function () {
        Route::get('/jadwal', [MobileController::class, 'getJadwal'])->name('jadwal');
        Route::post('/sync-sampling', [MobileController::class, 'syncSampling'])->name('sync-sampling');
        Route::post('/upload-sample-image', [MobileController::class, 'uploadSampleImage'])->name('upload-sample-image');
    });

    // ── Registrasi Sample ────────────────────────────────────────────────
    Route::prefix('registrasi-sample')->name('registrasi-sample.')->group(function () {
        Route::get('/', [RegistrasiSampleController::class, 'index'])
            ->middleware('permission:sample.view')
            ->name('index');
        Route::post('/', [RegistrasiSampleController::class, 'store'])
            ->middleware('permission:sample.register')
            ->name('store');
    });

    // ── Parameter Uji ────────────────────────────────────────────────────
    Route::get('/parameter-uji', [ParameterUjiController::class, 'index'])
        ->middleware('permission:parameter_uji.view')
        ->name('parameter-uji.index');

    // ── Hasil Uji ────────────────────────────────────────────────────────
    Route::prefix('hasil-uji')->name('hasil-uji.')->group(function () {
        Route::post('/', [HasilUjiController::class, 'store'])
            ->middleware('permission:hasil_uji.create')
            ->name('store');
        Route::get('/pending-qc', [HasilUjiController::class, 'pendingQc'])
            ->middleware('permission:qc.view')
            ->name('pending-qc');
    });

    // ── QC (Quality Control) ─────────────────────────────────────────────
    Route::prefix('qc')->name('qc.')->group(function () {
        Route::get('/history', [QcController::class, 'history'])
            ->middleware('permission:qc.view')
            ->name('history');
        Route::post('/approve', [QcController::class, 'approve'])
            ->middleware('permission:qc.verify')
            ->name('approve');
        Route::post('/reject', [QcController::class, 'reject'])
            ->middleware('permission:qc.reject')
            ->name('reject');
    });

    // ── Laporan ──────────────────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::post('/generate', [LaporanController::class, 'generate'])
            ->middleware('permission:laporan.create')
            ->name('generate');
        Route::get('/draft', [LaporanController::class, 'drafts'])
            ->middleware('permission:laporan.view')
            ->name('drafts');
        Route::get('/final', [LaporanController::class, 'finalReports'])
            ->middleware('permission:laporan_final.view')
            ->name('final');
        Route::post('/{id}/submit', [LaporanController::class, 'submit'])
            ->middleware('permission:laporan.create')
            ->name('submit');
        Route::get('/{id}/download', [LaporanController::class, 'download'])
            ->middleware('permission:laporan.view')
            ->name('download');
    });

    // ── Approval (Kepala UPTD) ───────────────────────────────────────────
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/pending', [ApprovalController::class, 'pending'])
            ->middleware('permission:approval.view')
            ->name('pending');
        Route::post('/final', [ApprovalController::class, 'approveFinal'])
            ->middleware('permission:approval.approve')
            ->name('final');
        Route::post('/reject', [ApprovalController::class, 'rejectFinal'])
            ->middleware('permission:approval.reject')
            ->name('reject');
    });

    // ── Notifications ────────────────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/read', [NotificationController::class, 'markAsRead'])->name('read');
    });

    // ── Activity Logs ────────────────────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('permission:activity_log.view')
        ->name('activity-logs.index');

    // ── Dashboard ────────────────────────────────────────────────────────
    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])
        ->name('dashboard.summary');
});
