<?php

namespace App\Services;

use App\Enums\PengujianStatus;
use App\Enums\QcStatus;
use App\Models\HasilUji;
use App\Models\ParameterUji;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class HasilUjiService
{
    /**
     * Input test results (creates as DRAFT).
     */
    public function create(array $data): HasilUji
    {
        $hasilUji = HasilUji::where('sample_id', $data['sample_id'])
            ->where('parameter_uji_id', $data['parameter_uji_id'])
            ->first();

        if ($hasilUji) {
            // Check status. If it's already pending QC or approved, do not allow editing!
            if (in_array($hasilUji->status_hasil, [PengujianStatus::PENDING_QC->value, PengujianStatus::APPROVED->value])) {
                throw new \Exception('Hasil uji untuk parameter ini sudah diproses dan tidak dapat diubah lagi.');
            }

            $hasilUji->update([
                'analis_id' => Auth::id() ?: \App\Models\User::first()?->id,
                'nilai_hasil' => $data['nilai_hasil'],
                'status_hasil' => PengujianStatus::PENDING_QC->value,
                'metode_pengujian' => $data['metode_pengujian'],
                'catatan' => $data['catatan'] ?? null,
                'diuji_pada' => now(),
            ]);
        } else {
            $hasilUji = HasilUji::create([
                'sample_id' => $data['sample_id'],
                'parameter_uji_id' => $data['parameter_uji_id'],
                'analis_id' => Auth::id() ?: \App\Models\User::first()?->id,
                'nilai_hasil' => $data['nilai_hasil'],
                'status_hasil' => PengujianStatus::PENDING_QC->value,
                'metode_pengujian' => $data['metode_pengujian'],
                'catatan' => $data['catatan'] ?? null,
                'status_qc' => QcStatus::PENDING->value,
                'diuji_pada' => now(),
            ]);
        }

        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Submit test results for QC review.
     * Transitions from DRAFT to PENDING_QC.
     */
    public function submitForQc(string $hasilUjiId): HasilUji
    {
        $hasilUji = HasilUji::findOrFail($hasilUjiId);

        if ($hasilUji->status_hasil !== PengujianStatus::DRAFT->value) {
            throw new \Exception('Hasil uji harus berstatus DRAFT untuk disubmit ke QC');
        }

        $hasilUji->update(['status_hasil' => PengujianStatus::PENDING_QC->value]);
        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Transition test result to APPROVED.
     * Called by QcService after QC approval.
     */
    public function transitionToApproved(string $hasilUjiId): HasilUji
    {
        $hasilUji = HasilUji::findOrFail($hasilUjiId);

        if ($hasilUji->status_hasil !== PengujianStatus::PENDING_QC->value) {
            throw new \Exception('Hasil uji harus berstatus PENDING_QC untuk disetujui');
        }

        $hasilUji->update(['status_hasil' => PengujianStatus::APPROVED->value]);
        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Transition test result to REJECTED.
     * Called by QcService after QC rejection.
     */
    public function transitionToRejected(string $hasilUjiId): HasilUji
    {
        $hasilUji = HasilUji::findOrFail($hasilUjiId);

        if ($hasilUji->status_hasil !== PengujianStatus::PENDING_QC->value) {
            throw new \Exception('Hasil uji harus berstatus PENDING_QC untuk ditolak');
        }

        $hasilUji->update(['status_hasil' => PengujianStatus::REJECTED->value]);
        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Transition rejected test result back to DRAFT for revision.
     */
    public function transitionToDraft(string $hasilUjiId): HasilUji
    {
        $hasilUji = HasilUji::findOrFail($hasilUjiId);

        if ($hasilUji->status_hasil !== PengujianStatus::REJECTED->value) {
            throw new \Exception('Hanya hasil uji yang ditolak yang bisa dikembalikan ke DRAFT');
        }

        $hasilUji->update(['status_hasil' => PengujianStatus::DRAFT->value]);
        $hasilUji->load(['sample', 'parameterUji', 'analis']);

        return $hasilUji;
    }

    /**
     * Get test results pending QC verification.
     */
    public function getPendingQc(): LengthAwarePaginator
    {
        return HasilUji::with(['sample.registrasiSample.permohonan', 'parameterUji', 'analis'])
            ->where('status_hasil', PengujianStatus::PENDING_QC->value)
            ->orderByDesc('diuji_pada')
            ->paginate(15);
    }

    /**
     * Get all parameter uji.
     */
    public function getAllParameters(?string $kategori = null, ?string $tipePengujian = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = ParameterUji::where('is_active', true);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($tipePengujian) {
            $query->where('tipe_pengujian', $tipePengujian);
        }

        return $query->orderBy('nama_parameter')->get();
    }
}
