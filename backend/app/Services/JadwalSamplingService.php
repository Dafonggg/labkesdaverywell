<?php

namespace App\Services;

use App\Enums\PermohonanStatus;
use App\Models\JadwalSampling;
use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class JadwalSamplingService
{
    public function __construct(
        protected PermohonanService $permohonanService
    ) {}

    /**
     * Get all jadwal sampling with pagination.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return JadwalSampling::with(['petugasLapangan', 'permohonan'])
            ->orderByDesc('tanggal_sampling')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new jadwal sampling and notify the field officer.
     * Validates that payment is completed before scheduling (workflow rule).
     */
    public function create(array $data): JadwalSampling
    {
        return DB::transaction(function () use ($data) {
            // Validate that permohonan has been paid (workflow enforcement)
            $permohonan = $this->permohonanService->getById($data['permohonan_id']);
            
            if ($permohonan->status !== PermohonanStatus::PAID->value) {
                throw new \Exception('Tidak bisa membuat jadwal sebelum pembayaran selesai. Status permohonan harus PAID.');
            }

            $data['status'] = 'scheduled';

            $jadwal = JadwalSampling::create($data);

            // Notify the assigned field officer
            Notification::create([
                'user_id' => $data['petugas_lapangan_id'],
                'title' => 'Jadwal Sampling Baru',
                'message' => "Anda memiliki jadwal sampling baru pada tanggal {$data['tanggal_sampling']} di lokasi {$data['lokasi']}",
                'type' => 'jadwal_baru',
                'is_read' => false,
                'created_at' => now(),
            ]);

            // Transition permohonan to SCHEDULED status (workflow enforcement)
            $this->permohonanService->transitionToScheduled($data['permohonan_id']);

            $jadwal->load(['petugasLapangan', 'permohonan']);

            return $jadwal;
        });
    }

    /**
     * Get jadwal for mobile (petugas lapangan).
     */
    public function getForMobile(string $userId): Collection
    {
        return JadwalSampling::with(['permohonan', 'samples'])
            ->where('petugas_lapangan_id', $userId)
            ->whereIn('status', ['scheduled', 'in_progress', 'dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_sampling')
            ->get();
    }

    /**
     * Update jadwal status (dijadwalkan → berlangsung → selesai).
     * Called by MobileController when sampling data is synced.
     */
    public function updateStatus(string $jadwalId, string $newStatus): JadwalSampling
    {
        $jadwal = JadwalSampling::findOrFail($jadwalId);
        $jadwal->status = $newStatus;
        $jadwal->save();
        return $jadwal;
    }
}
