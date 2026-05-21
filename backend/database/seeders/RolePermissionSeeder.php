<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Map permissions to roles.
     */
    public function run(): void
    {
        $rolePermissions = [
            // Admin — full access
            'admin' => [
                'permohonan.create', 'permohonan.view', 'permohonan.update', 'permohonan.delete', 'permohonan.approve',
                'payment.create', 'payment.view', 'payment.update',
                'jadwal.create', 'jadwal.view', 'jadwal.update', 'jadwal.delete',
                'sample.register', 'sample.view', 'sample.update', 'sample.upload_file',
                'parameter_uji.create', 'parameter_uji.view', 'parameter_uji.update', 'parameter_uji.delete',
                'hasil_uji.create', 'hasil_uji.view', 'hasil_uji.update', 'hasil_uji.upload_file',
                'qc.verify', 'qc.view', 'qc.reject',
                'laporan.create', 'laporan.view', 'laporan.update',
                'approval.approve', 'approval.reject', 'approval.view',
                'laporan_final.view', 'laporan_final.finalize',
                'arsip.create', 'arsip.view',
                'user.create', 'user.view', 'user.update', 'user.delete',
                'role.create', 'role.view', 'role.update', 'role.delete',
                'activity_log.view',
                'notification.view', 'notification.create',
                'sync.view', 'sync.manage',
            ],

            // Petugas Lapangan — sampling & field work
            'petugas_lapangan' => [
                'permohonan.view',
                'jadwal.view', 'jadwal.update',
                'sample.register', 'sample.view', 'sample.update', 'sample.upload_file',
                'notification.view',
                'sync.view', 'sync.manage',
            ],

            // Petugas Lab — lab testing
            'petugas_lab' => [
                'permohonan.view',
                'sample.view',
                'parameter_uji.view',
                'hasil_uji.create', 'hasil_uji.view', 'hasil_uji.update', 'hasil_uji.upload_file',
                'notification.view',
            ],

            // QC — quality control verification
            'qc' => [
                'permohonan.view',
                'sample.view',
                'parameter_uji.view',
                'hasil_uji.view',
                'qc.verify', 'qc.view', 'qc.reject',
                'notification.view',
            ],

            // Analis — analysis & reporting
            'analis' => [
                'permohonan.view',
                'sample.view',
                'parameter_uji.view',
                'hasil_uji.view',
                'qc.view',
                'laporan.create', 'laporan.view', 'laporan.update',
                'laporan_final.view', 'laporan_final.finalize',
                'arsip.create', 'arsip.view',
                'notification.view',
            ],

            // Kepala UPTD — approval authority
            'kepala_uptd' => [
                'permohonan.view', 'permohonan.approve',
                'sample.view',
                'hasil_uji.view',
                'qc.view',
                'laporan.view',
                'approval.approve', 'approval.reject', 'approval.view',
                'laporan_final.view',
                'arsip.view',
                'activity_log.view',
                'notification.view',
            ],
        ];

        foreach ($rolePermissions as $roleCode => $permissionCodes) {
            $role = Role::where('code', $roleCode)->first();

            if (!$role) {
                continue;
            }

            $permissionIds = Permission::whereIn('code', $permissionCodes)
                ->pluck('id')
                ->toArray();

            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}
