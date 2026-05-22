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
        return RegistrasiSample::with(['permohonan', 'pembuat', 'samples.hasilUji.parameterUji'])
            ->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Register a sample with auto-generated nomor_registrasi and kode_sample.
     */
    public function register(array $data): RegistrasiSample
    {
        return DB::transaction(function () use ($data) {
            $registrasi = RegistrasiSample::where('permohonan_id', $data['permohonan_id'])->first();

            if ($registrasi) {
                // Synced from mobile app. We find the existing sample, or create one if not exists.
                $sample = $registrasi->samples()->first();
                if (!$sample) {
                    $sample = \App\Models\Sample::create([
                        'registrasi_sample_id' => $registrasi->id,
                        'jenis_sample' => $data['jenis_sample'],
                        'lokasi_pengambilan' => $data['nama_sample'],
                        'kondisi_sample' => 'baik',
                        'status' => 'sampled',
                    ]);
                } else {
                    $sample->update([
                        'jenis_sample' => $data['jenis_sample'],
                        'lokasi_pengambilan' => $data['nama_sample'],
                    ]);
                }
            } else {
                $registrasi = RegistrasiSample::create([
                    'permohonan_id' => $data['permohonan_id'],
                    'nomor_registrasi' => NumberGenerator::generateNomorRegistrasi(),
                    'kode_sample' => NumberGenerator::generateKodeSample(),
                    'tanggal_registrasi' => now(),
                    'status' => 'registered',
                    'dibuat_oleh' => Auth::id() ?: \App\Models\User::first()?->id,
                ]);

                // Create the physical sample record
                $sample = \App\Models\Sample::create([
                    'registrasi_sample_id' => $registrasi->id,
                    'jenis_sample' => $data['jenis_sample'],
                    'lokasi_pengambilan' => $data['nama_sample'], // Custom sample name/source mapped to lokasi_pengambilan
                    'kondisi_sample' => 'baik',
                    'status' => 'sampled',
                ]);
            }

            // Create the HasilUji test parameters
            foreach ($data['parameters'] as $paramName) {
                // Find or create ParameterUji
                $parameterUji = \App\Models\ParameterUji::firstOrCreate(
                    [
                        'nama_parameter' => $paramName,
                        'kategori' => $data['jenis_sample'],
                    ],
                    [
                        'satuan' => $this->getDefaultSatuan($paramName),
                        'baku_mutu_max' => $this->getDefaultBakuMutu($paramName),
                        'is_active' => true,
                    ]
                );

                // Find analyst user or default to current user / first analyst
                $analist = \App\Models\User::whereHas('role', function ($query) {
                    $query->where('code', 'analis');
                })->first() ?: Auth::user() ?: \App\Models\User::first();

                // Check if this parameter already exists as an HasilUji (to avoid duplicate parameters)
                $exists = \App\Models\HasilUji::where('sample_id', $sample->id)
                    ->where('parameter_uji_id', $parameterUji->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\HasilUji::create([
                        'sample_id' => $sample->id,
                        'parameter_uji_id' => $parameterUji->id,
                        'analis_id' => $analist?->id,
                        'nilai_hasil' => null,
                        'status_hasil' => \App\Enums\PengujianStatus::DRAFT->value,
                        'status_qc' => \App\Enums\QcStatus::PENDING->value,
                        'metode_pengujian' => $parameterUji->metode_uji ?? 'Manual',
                    ]);
                }
            }

            $registrasi->load(['pembuat', 'samples.hasilUji.parameterUji']);

            return $registrasi;
        });
    }

    /**
     * Get default units for common test parameters.
     */
    protected function getDefaultSatuan(string $param): string
    {
        $satuans = [
            // Air
            'Suhu' => '°C', 'pH' => '-', 'TDS' => 'mg/L', 'DO' => 'mg/L', 'Kekeruhan' => 'NTU',
            'Nitrat' => 'mg/L', 'Besi' => 'mg/L', 'Mangan' => 'mg/L', 'Coliform' => 'CFU/100mL',
            // Air Limbah
            'BOD' => 'mg/L', 'COD' => 'mg/L', 'Minyak & Lemak' => 'mg/L', 'Amonia' => 'mg/L', 'TSS' => 'mg/L',
            // Makanan & Minuman
            'Formalin' => '-', 'Boraks' => '-', 'Pewarna' => '-', 'Total Plate Count' => 'CFU/g',
            'E.coli' => 'CFU/g', 'Salmonella' => 'per 25g',
            // Udara
            'PM2.5' => 'µg/m³', 'PM10' => 'µg/m³', 'SO2' => 'µg/m³', 'NO2' => 'µg/m³', 'CO' => 'µg/m³',
            // Tanah
            'pH tanah' => '-', 'Kadar logam berat' => 'mg/kg', 'Nitrogen' => '%', 'Fosfor' => 'ppm',
            // Swab Lingkungan
            'Angka kuman' => 'CFU/cm²', 'Sterilitas' => '-'
        ];
        return $satuans[$param] ?? '-';
    }

    /**
     * Get default maximum standard limit (baku mutu) for common test parameters.
     */
    protected function getDefaultBakuMutu(string $param): ?float
    {
        $bakuMutu = [
            // Air
            'Suhu' => 30, 'pH' => 8.5, 'TDS' => 500, 'DO' => null, 'Kekeruhan' => 5,
            'Nitrat' => 10, 'Besi' => 0.3, 'Mangan' => 0.1, 'Coliform' => 0,
            // Air Limbah
            'BOD' => 30, 'COD' => 100, 'Minyak & Lemak' => 5, 'Amonia' => 0.5, 'TSS' => 50,
            // Makanan & Minuman
            'Formalin' => 0, 'Boraks' => 0, 'Pewarna' => 0, 'Total Plate Count' => 10000,
            'E.coli' => 0, 'Salmonella' => 0,
            // Udara
            'PM2.5' => 55, 'PM10' => 75, 'SO2' => 150, 'NO2' => 200, 'CO' => 10000,
            // Tanah
            'pH tanah' => 8.0, 'Kadar logam berat' => 10, 'Nitrogen' => 0.5, 'Fosfor' => 15,
            // Swab Lingkungan
            'Angka kuman' => 100, 'Sterilitas' => 0
        ];
        return $bakuMutu[$param] ?? null;
    }
}
