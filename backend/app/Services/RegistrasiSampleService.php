<?php

namespace App\Services;

use App\Helpers\NumberGenerator;
use App\Models\RegistrasiSample;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrasiSampleService
{
    /**
     * Get all registered samples with pagination.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return RegistrasiSample::with(['permohonan', 'pembuat'])
            ->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Register a sample with auto-generated nomor_registrasi and kode_sample.
     */
    public function register(array $data): RegistrasiSample
    {
        return DB::transaction(function () use ($data) {
            $registrasi = RegistrasiSample::create([
                'permohonan_id' => $data['permohonan_id'],
                'nomor_registrasi' => NumberGenerator::generateNomorRegistrasi(),
                'kode_sample' => NumberGenerator::generateKodeSample(),
                'tanggal_registrasi' => now(),
                'status' => 'registered',
                'dibuat_oleh' => Auth::id(),
            ]);

            $registrasi->load('pembuat');

            return $registrasi;
        });
    }
}
