<?php

namespace App\Helpers;

use App\Models\DraftLaporan;
use App\Models\PermohonanPengujian;
use App\Models\RegistrasiSample;

class NumberGenerator
{
    /**
     * Generate nomor permohonan: PMH/YYYY/NNN
     */
    public static function generateNomorPermohonan(): string
    {
        $year = now()->format('Y');
        $prefix = "PMH/{$year}/";

        $lastNumber = PermohonanPengujian::where('nomor_permohonan', 'like', "{$prefix}%")
            ->orderByDesc('nomor_permohonan')
            ->value('nomor_permohonan');

        if ($lastNumber) {
            $lastSequence = (int) str_replace($prefix, '', $lastNumber);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor registrasi: REG/YYYY/NNN
     */
    public static function generateNomorRegistrasi(): string
    {
        $year = now()->format('Y');
        $prefix = "REG/{$year}/";

        $lastNumber = RegistrasiSample::where('nomor_registrasi', 'like', "{$prefix}%")
            ->orderByDesc('nomor_registrasi')
            ->value('nomor_registrasi');

        if ($lastNumber) {
            $lastSequence = (int) str_replace($prefix, '', $lastNumber);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode sample: SPL-YYYYMMDD-NNN
     */
    public static function generateKodeSample(): string
    {
        $date = now()->format('Ymd');
        $prefix = "SPL-{$date}-";

        $lastCode = RegistrasiSample::where('kode_sample', 'like', "{$prefix}%")
            ->orderByDesc('kode_sample')
            ->value('kode_sample');

        if ($lastCode) {
            $lastSequence = (int) str_replace($prefix, '', $lastCode);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor laporan: LAP/YYYY/NNN
     */
    public static function generateNomorLaporan(): string
    {
        $year = now()->format('Y');
        $prefix = "LAP/{$year}/";

        $lastNumber = DraftLaporan::where('nomor_laporan', 'like', "{$prefix}%")
            ->orderByDesc('nomor_laporan')
            ->value('nomor_laporan');

        if ($lastNumber) {
            $lastSequence = (int) str_replace($prefix, '', $lastNumber);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
    }
}
