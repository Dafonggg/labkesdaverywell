<?php

namespace App\Services;

use App\Enums\QcStatus;
use App\Models\HasilUji;
use App\Models\Notification;
use App\Models\VerifikasiQc;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QcService
{
    public function __construct(
        protected HasilUjiService $hasilUjiService
    ) {}

    /**
     * Get QC verification history with pagination.
     */
    public function getHistory(array $filters = []): LengthAwarePaginator
    {
        return VerifikasiQc::with(['hasilUji.parameterUji', 'hasilUji.sample', 'qcOfficer'])
            ->orderByDesc('diverifikasi_pada')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Approve a test result QC.
     * Transitions pengujian status from PENDING_QC to APPROVED.
     */
    public function approve(array $data): VerifikasiQc
    {
        return DB::transaction(function () use ($data) {
            // Transition pengujian status to APPROVED (workflow enforcement)
            $this->hasilUjiService->transitionToApproved($data['hasil_uji_id']);

            $hasilUji = HasilUji::findOrFail($data['hasil_uji_id']);

            // Update legacy status_qc field for backward compatibility
            $hasilUji->update([
                'status_qc' => QcStatus::APPROVED->value,
            ]);

            // Create verification record
            $verifikasi = VerifikasiQc::create([
                'hasil_uji_id' => $data['hasil_uji_id'],
                'qc_id' => Auth::id(),
                'status' => QcStatus::APPROVED->value,
                'catatan' => $data['catatan'] ?? null,
                'diverifikasi_pada' => now(),
            ]);

            $verifikasi->load('qcOfficer');

            return $verifikasi;
        });
    }

    /**
     * Reject a test result QC with reason.
     * Transitions pengujian status from PENDING_QC to REJECTED.
     */
    public function reject(array $data): VerifikasiQc
    {
        return DB::transaction(function () use ($data) {
            // Transition pengujian status to REJECTED (workflow enforcement)
            $this->hasilUjiService->transitionToRejected($data['hasil_uji_id']);

            $hasilUji = HasilUji::with('analis')->findOrFail($data['hasil_uji_id']);

            // Update legacy status_qc field for backward compatibility
            $hasilUji->update([
                'status_qc' => QcStatus::REJECTED->value,
            ]);

            // Create verification record
            $verifikasi = VerifikasiQc::create([
                'hasil_uji_id' => $data['hasil_uji_id'],
                'qc_id' => Auth::id(),
                'status' => QcStatus::REJECTED->value,
                'catatan' => $data['catatan'],
                'diverifikasi_pada' => now(),
            ]);

            // Notify the analis about rejection
            if ($hasilUji->analis_id) {
                Notification::create([
                    'user_id' => $hasilUji->analis_id,
                    'title' => 'Hasil Uji Ditolak QC',
                    'message' => "Hasil uji Anda ditolak oleh QC. Alasan: {$data['catatan']}",
                    'type' => 'qc_reject',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            $verifikasi->load('qcOfficer');

            return $verifikasi;
        });
    }
}
