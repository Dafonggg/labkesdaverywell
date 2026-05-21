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
    /**
     * Get QC verification history with pagination.
     */
    public function getHistory(array $filters = []): LengthAwarePaginator
    {
        return VerifikasiQc::with(['hasilUji', 'qcOfficer'])
            ->orderByDesc('diverifikasi_pada')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Approve a test result QC.
     */
    public function approve(array $data): VerifikasiQc
    {
        return DB::transaction(function () use ($data) {
            $hasilUji = HasilUji::findOrFail($data['hasil_uji_id']);

            // Update hasil uji status
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
     */
    public function reject(array $data): VerifikasiQc
    {
        return DB::transaction(function () use ($data) {
            $hasilUji = HasilUji::with('analis')->findOrFail($data['hasil_uji_id']);

            // Update hasil uji status
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
