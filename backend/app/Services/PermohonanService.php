<?php

namespace App\Services;

use App\Helpers\NumberGenerator;
use App\Models\PermohonanPengujian;
use App\Enums\PermohonanStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PermohonanService
{
    /**
     * Get all permohonan with filters, search, and pagination.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = PermohonanPengujian::with(['pemohon']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_permohonan', 'like', "%{$search}%")
                    ->orWhere('nama_pemohon', 'like', "%{$search}%")
                    ->orWhere('nama_instansi', 'like', "%{$search}%")
                    ->orWhere('jenis_sample', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new permohonan.
     */
    public function create(array $data): PermohonanPengujian
    {
        $data['nomor_permohonan'] = NumberGenerator::generateNomorPermohonan();
        $data['status'] = PermohonanStatus::PENDING->value;
        $data['dibuat_oleh'] = Auth::id();

        $permohonan = PermohonanPengujian::create($data);
        $permohonan->load('pemohon');

        return $permohonan;
    }

    /**
     * Get a single permohonan by ID with all relationships.
     */
    public function getById(string $id): PermohonanPengujian
    {
        return PermohonanPengujian::with([
            'pemohon',
            'payments',
            'jadwalSampling.petugasLapangan',
            'registrasiSample.samples',
            'draftLaporan',
        ])->findOrFail($id);
    }

    /**
     * Update a permohonan.
     */
    public function update(string $id, array $data): PermohonanPengujian
    {
        $permohonan = PermohonanPengujian::findOrFail($id);
        $permohonan->update($data);
        $permohonan->load('pemohon');

        return $permohonan;
    }

    /**
     * Soft delete a permohonan.
     */
    public function delete(string $id): void
    {
        $permohonan = PermohonanPengujian::findOrFail($id);
        $permohonan->delete();
    }
}
