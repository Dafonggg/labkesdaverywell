<?php

namespace App\Services;

use App\Enums\LaporanStatus;
use App\Helpers\NumberGenerator;
use App\Models\DraftLaporan;
use App\Models\LaporanFinal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    /**
     * Generate a draft report from a permohonan.
     */
    public function generateDraft(array $data): DraftLaporan
    {
        return DB::transaction(function () use ($data) {
            $draft = DraftLaporan::create([
                'permohonan_id' => $data['permohonan_id'],
                'analis_id' => Auth::id(),
                'nomor_laporan' => NumberGenerator::generateNomorLaporan(),
                'status' => LaporanStatus::DRAFT->value,
                'catatan' => $data['catatan'] ?? null,
                'dibuat_pada' => now(),
            ]);

            $draft->load(['permohonan', 'analis']);

            return $draft;
        });
    }

    /**
     * Get draft reports with pagination.
     */
    public function getDrafts(): LengthAwarePaginator
    {
        return DraftLaporan::with(['permohonan', 'analis', 'persetujuan'])
            ->orderByDesc('dibuat_pada')
            ->paginate(15);
    }

    /**
     * Get a single draft laporan by ID.
     */
    public function getById(string $id): DraftLaporan
    {
        return DraftLaporan::with([
            'permohonan',
            'analis',
            'persetujuan.kepalaUptd',
            'laporanFinal',
        ])->findOrFail($id);
    }

    /**
     * Get final reports with pagination.
     */
    public function getLaporanFinal(array $filters = []): LengthAwarePaginator
    {
        return LaporanFinal::with(['draftLaporan.permohonan'])
            ->orderByDesc('finalized_at')
            ->paginate($filters['per_page'] ?? 15);
    }
}
