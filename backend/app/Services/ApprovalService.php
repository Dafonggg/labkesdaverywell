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

class ApprovalService
{
    public function __construct(
        protected LaporanService $laporanService
    ) {}

    /**
     * Final approval of a laporan by Kepala UPTD.
     * Uses LaporanService workflow methods to enforce proper state transitions.
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

            // Transition draft to APPROVED status (workflow enforcement)
            $this->laporanService->transitionToApproved($data['laporan_id']);

            // Finalize report (APPROVED → FINAL)
            $laporanFinal = $this->laporanService->finalize($data['laporan_id']);

            // Archive report (FINAL → ARCHIVED)
            $this->laporanService->archive($laporanFinal->id);

            // Create archive record for tracking
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
     * Returns draft to DRAFT status for revision (workflow enforcement).
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

            // Transition draft back to DRAFT for revision (workflow enforcement)
            $this->laporanService->transitionToDraft($data['laporan_id']);

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
            ->where('status', LaporanStatus::PENDING_APPROVAL->value)
            ->orderByDesc('dibuat_pada')
            ->paginate($filters['per_page'] ?? 15);
    }
}
