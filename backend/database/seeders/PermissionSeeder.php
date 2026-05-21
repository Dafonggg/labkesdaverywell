<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the permissions table.
     *
     * Convention: module.action
     */
    public function run(): void
    {
        $permissions = [
            // Permohonan Pengujian
            ['name' => 'Buat Permohonan', 'code' => 'permohonan.create'],
            ['name' => 'Lihat Permohonan', 'code' => 'permohonan.view'],
            ['name' => 'Update Permohonan', 'code' => 'permohonan.update'],
            ['name' => 'Hapus Permohonan', 'code' => 'permohonan.delete'],
            ['name' => 'Approve Permohonan', 'code' => 'permohonan.approve'],

            // Payment
            ['name' => 'Buat Pembayaran', 'code' => 'payment.create'],
            ['name' => 'Lihat Pembayaran', 'code' => 'payment.view'],
            ['name' => 'Update Pembayaran', 'code' => 'payment.update'],

            // Jadwal Sampling
            ['name' => 'Buat Jadwal Sampling', 'code' => 'jadwal.create'],
            ['name' => 'Lihat Jadwal Sampling', 'code' => 'jadwal.view'],
            ['name' => 'Update Jadwal Sampling', 'code' => 'jadwal.update'],
            ['name' => 'Hapus Jadwal Sampling', 'code' => 'jadwal.delete'],

            // Registrasi & Sample
            ['name' => 'Registrasi Sampel', 'code' => 'sample.register'],
            ['name' => 'Lihat Sampel', 'code' => 'sample.view'],
            ['name' => 'Update Sampel', 'code' => 'sample.update'],
            ['name' => 'Upload File Sampel', 'code' => 'sample.upload_file'],

            // Parameter Uji
            ['name' => 'Buat Parameter Uji', 'code' => 'parameter_uji.create'],
            ['name' => 'Lihat Parameter Uji', 'code' => 'parameter_uji.view'],
            ['name' => 'Update Parameter Uji', 'code' => 'parameter_uji.update'],
            ['name' => 'Hapus Parameter Uji', 'code' => 'parameter_uji.delete'],

            // Hasil Uji
            ['name' => 'Input Hasil Uji', 'code' => 'hasil_uji.create'],
            ['name' => 'Lihat Hasil Uji', 'code' => 'hasil_uji.view'],
            ['name' => 'Update Hasil Uji', 'code' => 'hasil_uji.update'],
            ['name' => 'Upload File Hasil Uji', 'code' => 'hasil_uji.upload_file'],

            // Verifikasi QC
            ['name' => 'Verifikasi QC', 'code' => 'qc.verify'],
            ['name' => 'Lihat Verifikasi QC', 'code' => 'qc.view'],
            ['name' => 'Tolak QC', 'code' => 'qc.reject'],

            // Draft Laporan
            ['name' => 'Buat Draft Laporan', 'code' => 'laporan.create'],
            ['name' => 'Lihat Laporan', 'code' => 'laporan.view'],
            ['name' => 'Update Draft Laporan', 'code' => 'laporan.update'],

            // Persetujuan / Approval
            ['name' => 'Approve Laporan', 'code' => 'approval.approve'],
            ['name' => 'Tolak Laporan', 'code' => 'approval.reject'],
            ['name' => 'Lihat Approval', 'code' => 'approval.view'],

            // Laporan Final
            ['name' => 'Lihat Laporan Final', 'code' => 'laporan_final.view'],
            ['name' => 'Finalisasi Laporan', 'code' => 'laporan_final.finalize'],

            // Arsip
            ['name' => 'Buat Arsip', 'code' => 'arsip.create'],
            ['name' => 'Lihat Arsip', 'code' => 'arsip.view'],

            // User Management
            ['name' => 'Buat User', 'code' => 'user.create'],
            ['name' => 'Lihat User', 'code' => 'user.view'],
            ['name' => 'Update User', 'code' => 'user.update'],
            ['name' => 'Hapus User', 'code' => 'user.delete'],

            // Role Management
            ['name' => 'Buat Role', 'code' => 'role.create'],
            ['name' => 'Lihat Role', 'code' => 'role.view'],
            ['name' => 'Update Role', 'code' => 'role.update'],
            ['name' => 'Hapus Role', 'code' => 'role.delete'],

            // Activity Log
            ['name' => 'Lihat Activity Log', 'code' => 'activity_log.view'],

            // Notification
            ['name' => 'Lihat Notifikasi', 'code' => 'notification.view'],
            ['name' => 'Buat Notifikasi', 'code' => 'notification.create'],

            // Sync Management
            ['name' => 'Lihat Sync Log', 'code' => 'sync.view'],
            ['name' => 'Kelola Sync', 'code' => 'sync.manage'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['code' => $permission['code']],
                $permission
            );
        }
    }
}
