<?php

namespace App\Services;

use App\Models\JadwalSampling;
use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class JadwalSamplingService
{
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
     */
    public function create(array $data): JadwalSampling
    {
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

        $jadwal->load(['petugasLapangan', 'permohonan']);

        return $jadwal;
    }

    /**
     * Get jadwal for mobile (petugas lapangan).
     */
    public function getForMobile(string $userId): Collection
    {
        return JadwalSampling::with(['permohonan', 'samples'])
            ->where('petugas_lapangan_id', $userId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('tanggal_sampling')
            ->get();
    }
}
