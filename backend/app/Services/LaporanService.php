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
     * Submit draft for approval.
     * Transitions from DRAFT to PENDING_APPROVAL.
     */
    public function submitForApproval(string $draftId): DraftLaporan
    {
        $draft = DraftLaporan::findOrFail($draftId);

        if ($draft->status !== LaporanStatus::DRAFT->value) {
            throw new \Exception('Hanya draft dengan status DRAFT yang dapat disubmit untuk approval');
        }

        $draft->update(['status' => LaporanStatus::PENDING_APPROVAL->value]);
        $draft->load(['permohonan', 'analis']);

        return $draft;
    }

    /**
     * Transition draft to APPROVED.
     * Called by ApprovalService after Kepala UPTD approves.
     */
    public function transitionToApproved(string $draftId): DraftLaporan
    {
        $draft = DraftLaporan::findOrFail($draftId);

        if ($draft->status !== LaporanStatus::PENDING_APPROVAL->value) {
            throw new \Exception('Draft harus berstatus PENDING_APPROVAL untuk disetujui');
        }

        $draft->update(['status' => LaporanStatus::APPROVED->value]);
        $draft->load(['permohonan', 'analis']);

        return $draft;
    }

    /**
     * Reject draft and return to DRAFT for revision.
     * Called by ApprovalService when Kepala UPTD rejects.
     */
    public function transitionToDraft(string $draftId): DraftLaporan
    {
        $draft = DraftLaporan::findOrFail($draftId);

        if ($draft->status !== LaporanStatus::PENDING_APPROVAL->value) {
            throw new \Exception('Hanya draft dengan status PENDING_APPROVAL yang dapat ditolak');
        }

        $draft->update(['status' => LaporanStatus::DRAFT->value]);
        $draft->load(['permohonan', 'analis']);

        return $draft;
    }

    /**
     * Finalize report and generate final PDF.
     * Transitions from APPROVED to FINAL.
     */
    public function finalize(string $draftId): LaporanFinal
    {
        return DB::transaction(function () use ($draftId) {
            $draft = DraftLaporan::findOrFail($draftId);

            if ($draft->status !== LaporanStatus::APPROVED->value) {
                throw new \Exception('Draft harus berstatus APPROVED untuk difinalisasi');
            }

            // Create final report record
            $laporanFinal = LaporanFinal::create([
                'draft_laporan_id' => $draftId,
                'nomor_laporan' => $draft->nomor_laporan,
                'file_path' => null, // PDF generation will be implemented later
                'qr_code' => null,
                'hash_sha256' => null,
                'status' => LaporanStatus::FINAL->value,
                'finalized_at' => now(),
            ]);

            // Update draft status to FINAL
            $draft->update(['status' => LaporanStatus::FINAL->value]);

            $laporanFinal->load('draftLaporan.permohonan');

            return $laporanFinal;
        });
    }

    /**
     * Archive final report.
     * Transitions from FINAL to ARCHIVED.
     */
    public function archive(string $laporanFinalId): LaporanFinal
    {
        $laporanFinal = LaporanFinal::findOrFail($laporanFinalId);

        if ($laporanFinal->status !== LaporanStatus::FINAL->value) {
            throw new \Exception('Laporan harus berstatus FINAL untuk diarsipkan');
        }

        $laporanFinal->update(['status' => LaporanStatus::ARCHIVED->value]);
        $laporanFinal->load('draftLaporan.permohonan');

        return $laporanFinal;
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
