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
     * @return array{success_count: int, failed_count: int, errors: array}
     */
    public function batchSync(array $samples): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        DB::transaction(function () use ($samples, &$successCount, &$failedCount, &$errors) {
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

                    Sample::create([
                        'jadwal_sampling_id' => $sampleData['jadwal_sampling_id'],
                        'jenis_sample' => $sampleData['jenis_sample'],
                        'latitude' => $sampleData['latitude'] ?? null,
                        'longitude' => $sampleData['longitude'] ?? null,
                        'cuaca' => $sampleData['cuaca'] ?? null,
                        'suhu' => $sampleData['suhu'] ?? null,
                        'lokasi_pengambilan' => $sampleData['catatan'] ?? null,
                        'waktu_pengambilan' => $sampleData['waktu_pengambilan'],
                        'status' => 'collected',
                    ]);

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
