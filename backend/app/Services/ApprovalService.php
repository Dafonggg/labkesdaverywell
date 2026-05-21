<?php

namespace App\Services;

use App\Enums\LaporanStatus;
use App\Models\ArsipLaporan;
use App\Models\DraftLaporan;
use App\Models\LaporanFinal;
use App\Models\Notification;
use App\Models\PersetujuanLaporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApprovalService
{
    /**
     * Final approval of a laporan by Kepala UPTD.
     * Creates persetujuan, laporan_final, and arsip records in one transaction.
     */
    public function approveFinal(array $data): PersetujuanLaporan
    {
        return DB::transaction(function () use ($data) {
            $draftLaporan = DraftLaporan::with('analis')->findOrFail($data['laporan_id']);

            // Create approval record
            $persetujuan = PersetujuanLaporan::create([
                'draft_laporan_id' => $data['laporan_id'],
                'kepala_uptd_id' => Auth::id(),
                'status' => 'approved',
                'catatan' => $data['catatan'] ?? null,
                'approved_at' => now(),
            ]);

            // Update draft status
            $draftLaporan->update([
                'status' => LaporanStatus::APPROVED->value,
            ]);

            // Generate SHA256 hash for integrity
            $hashContent = $draftLaporan->id . $draftLaporan->nomor_laporan . now()->toISOString();
            $hash = hash('sha256', $hashContent);

            // Create final report
            $laporanFinal = LaporanFinal::create([
                'draft_laporan_id' => $draftLaporan->id,
                'nomor_laporan' => $draftLaporan->nomor_laporan,
                'hash_sha256' => $hash,
                'is_final' => true,
                'finalized_at' => now(),
            ]);

            // Create archive
            ArsipLaporan::create([
                'laporan_final_id' => $laporanFinal->id,
                'archived_at' => now(),
                'archived_by' => Auth::id(),
                'created_at' => now(),
            ]);

            // Notify analis
            if ($draftLaporan->analis_id) {
                Notification::create([
                    'user_id' => $draftLaporan->analis_id,
                    'title' => 'Laporan Disetujui',
                    'message' => "Laporan {$draftLaporan->nomor_laporan} telah disetujui oleh Kepala UPTD",
                    'type' => 'laporan_final',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            $persetujuan->load('kepalaUptd');

            return $persetujuan;
        });
    }

    /**
     * Reject a laporan and send back for revision.
     */
    public function rejectFinal(array $data): PersetujuanLaporan
    {
        return DB::transaction(function () use ($data) {
            $draftLaporan = DraftLaporan::with('analis')->findOrFail($data['laporan_id']);

            // Create rejection record
            $persetujuan = PersetujuanLaporan::create([
                'draft_laporan_id' => $data['laporan_id'],
                'kepala_uptd_id' => Auth::id(),
                'status' => 'rejected',
                'catatan' => $data['catatan'] ?? null,
                'approved_at' => now(),
            ]);

            // Update draft status back to revision
            $draftLaporan->update([
                'status' => LaporanStatus::REVISION->value,
            ]);

            // Notify analis about rejection
            if ($draftLaporan->analis_id) {
                Notification::create([
                    'user_id' => $draftLaporan->analis_id,
                    'title' => 'Laporan Ditolak',
                    'message' => "Laporan {$draftLaporan->nomor_laporan} ditolak oleh Kepala UPTD. Catatan: " . ($data['catatan'] ?? '-'),
                    'type' => 'laporan_rejected',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            $persetujuan->load('kepalaUptd');

            return $persetujuan;
        });
    }

    /**
     * Get pending approval drafts (laporan waiting for Kepala UPTD approval).
     */
    public function getPending(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return DraftLaporan::with(['permohonan', 'analis'])
            ->whereIn('status', [LaporanStatus::PENDING_APPROVAL->value, LaporanStatus::REVIEW->value])
            ->orderByDesc('dibuat_pada')
            ->paginate($filters['per_page'] ?? 15);
    }
}
