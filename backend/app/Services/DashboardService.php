<?php

namespace App\Services;

use App\Models\DraftLaporan;
use App\Models\HasilUji;
use App\Models\LaporanFinal;
use App\Models\PermohonanPengujian;

class DashboardService
{
    /**
     * Get dashboard summary statistics.
     *
     * @return array<string, int>
     */
    public function getSummary(): array
    {
        return [
            'permohonan' => PermohonanPengujian::count(),
            'permohonan_pending' => PermohonanPengujian::where('status', 'pending')->count(),
            'pending_qc' => HasilUji::where('status_qc', 'pending')->count(),
            'draft_laporan' => DraftLaporan::where('status', 'draft')->count(),
            'laporan_final' => LaporanFinal::where('is_final', true)->count(),
        ];
    }
}
