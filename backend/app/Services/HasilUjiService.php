<?php

namespace App\Services;

use App\Enums\QcStatus;
use App\Models\HasilUji;
use App\Models\ParameterUji;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class HasilUjiService
{
    /**
     * Input test results.
     */
    public function create(array $data): HasilUji
    {
        $hasilUji = HasilUji::create([
            'sample_id' => $data['sample_id'],
            'parameter_uji_id' => $data['parameter_uji_id'],
            'analis_id' => Auth::id(),
            'nilai_hasil' => $data['nilai_hasil'],
            'status_hasil' => 'completed',
            'metode_pengujian' => $data['metode_pengujian'],
            'catatan' => $data['catatan'] ?? null,
            'status_qc' => QcStatus::PENDING->value,
            'diuji_pada' => now(),
        ]);

        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Get test results pending QC verification.
     */
    public function getPendingQc(): LengthAwarePaginator
    {
        return HasilUji::with(['sample', 'parameterUji', 'analis'])
            ->where('status_qc', QcStatus::PENDING->value)
            ->orderByDesc('diuji_pada')
            ->paginate(15);
    }

    /**
     * Get all parameter uji.
     */
    public function getAllParameters(): \Illuminate\Database\Eloquent\Collection
    {
        return ParameterUji::where('is_active', true)
            ->orderBy('nama_parameter')
            ->get();
    }
}
