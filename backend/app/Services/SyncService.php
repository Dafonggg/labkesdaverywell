<?php

namespace App\Services;

use App\Enums\SyncStatus;
use App\Models\FailedSync;
use App\Models\Sample;
use App\Models\SampleFile;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SyncService
{
    /**
     * Batch sync sampling data from mobile.
     * Uses DB transaction, duplicate prevention via sync_id.
     *
     * @return array{success_count: int, failed_count: int, errors: array, sample_ids: array}
     */
    public function batchSync(array $samples): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $sampleIds = []; // maps sync_id => sample_id

        DB::transaction(function () use ($samples, &$successCount, &$failedCount, &$errors, &$sampleIds) {
            foreach ($samples as $index => $sampleData) {
                try {
                    // Duplicate prevention: check sync_id in sync_logs
                    $existingSync = SyncLog::where('sync_type', 'sampling')
                        ->where('response_message', 'like', "%{$sampleData['sync_id']}%")
                        ->exists();

                    if ($existingSync) {
                        $failedCount++;
                        $errors[] = [
                            'index' => $index,
                            'sync_id' => $sampleData['sync_id'],
                            'error' => 'Duplicate sync_id',
                        ];
                        continue;
                    }

                    // Retrieve the corresponding JadwalSampling to find the permohonan_id
                    $jadwal = \App\Models\JadwalSampling::find($sampleData['jadwal_sampling_id']);
                    if (!$jadwal) {
                        throw new \Exception("Jadwal sampling tidak ditemukan");
                    }

                    // Decode catatan to extract kode_sample if present
                    $kodeSample = null;
                    if (!empty($sampleData['catatan'])) {
                        $catatanDecoded = json_decode($sampleData['catatan'], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $kodeSample = $catatanDecoded['kode_sample'] ?? null;
                        }
                    }

                    // Find or create the RegistrasiSample for this permohonan and kode_sample
                    $registrasi = null;
                    if ($kodeSample) {
                        $registrasi = \App\Models\RegistrasiSample::where('permohonan_id', $jadwal->permohonan_id)
                            ->where('kode_sample', $kodeSample)
                            ->first();
                    }

                    if (!$registrasi) {
                        // Generate a safe unique kode_sample if not provided
                        $finalKodeSample = $kodeSample ?: \App\Helpers\NumberGenerator::generateKodeSample();
                        
                        $registrasi = \App\Models\RegistrasiSample::create([
                            'permohonan_id' => $jadwal->permohonan_id,
                            'nomor_registrasi' => \App\Helpers\NumberGenerator::generateNomorRegistrasi(),
                            'kode_sample' => $finalKodeSample,
                            'tanggal_registrasi' => now(),
                            'status' => 'registered',
                            'dibuat_oleh' => Auth::id() ?: \App\Models\User::first()?->id,
                        ]);
                    }

                    $sample = Sample::create([
                        'registrasi_sample_id' => $registrasi->id,
                        'jadwal_sampling_id' => $sampleData['jadwal_sampling_id'],
                        'jenis_sample' => $sampleData['jenis_sample'],
                        'kondisi_sample' => $sampleData['kondisi_sample'] ?? 'baik',
                        'metode_pengambilan' => $sampleData['metode_pengambilan'] ?? null,
                        'latitude' => $sampleData['latitude'] ?? null,
                        'longitude' => $sampleData['longitude'] ?? null,
                        'cuaca' => $sampleData['cuaca'] ?? null,
                        'suhu' => $sampleData['suhu'] ?? null,
                        'lokasi_pengambilan' => $sampleData['lokasi_pengambilan'] ?? null,
                        'catatan' => $sampleData['catatan'] ?? null,
                        'waktu_pengambilan' => $sampleData['waktu_pengambilan'],
                        'status' => 'collected',
                    ]);

                    // Map sync_id -> sample_id so mobile can upload photos
                    $sampleIds[$sampleData['sync_id']] = $sample->id;

                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = [
                        'index' => $index,
                        'sync_id' => $sampleData['sync_id'] ?? null,
                        'error' => $e->getMessage(),
                    ];

                    // Log failed sync
                    FailedSync::create([
                        'user_id' => Auth::id(),
                        'reference_id' => $sampleData['sync_id'] ?? null,
                        'payload' => $sampleData,
                        'error_message' => $e->getMessage(),
                        'retry_count' => 0,
                    ]);
                }
            }
        });

        // Create sync log
        $status = $failedCount === 0
            ? SyncStatus::SUCCESS->value
            : ($successCount === 0 ? SyncStatus::FAILED->value : SyncStatus::PARTIAL->value);

        SyncLog::create([
            'user_id' => Auth::id(),
            'sync_type' => 'sampling',
            'status' => $status,
            'total_data' => count($samples),
            'success_data' => $successCount,
            'failed_data' => $failedCount,
            'response_message' => json_encode([
                'sync_ids' => array_column($samples, 'sync_id'),
                'errors' => $errors,
            ]),
            'synced_at' => now(),
            'created_at' => now(),
        ]);

        return [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
            'sample_ids' => $sampleIds, // sync_id => sample_id map
        ];
    }

    /**
     * Upload a sample image.
     */
    public function uploadImage(string $sampleId, $image): SampleFile
    {
        $path = $image->store('samples/images', 'public');

        return SampleFile::create([
            'sample_id' => $sampleId,
            'file_path' => $path,
            'file_type' => $image->getClientOriginalExtension(),
            'file_name' => $image->getClientOriginalName(),
            'file_size' => $image->getSize(),
        ]);
    }
}
