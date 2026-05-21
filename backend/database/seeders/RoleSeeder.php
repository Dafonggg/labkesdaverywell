<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'code' => 'admin',
            ],
            [
                'name' => 'Petugas Lapangan',
                'code' => 'petugas_lapangan',
            ],
            [
                'name' => 'Petugas Lab',
                'code' => 'petugas_lab',
            ],
            [
                'name' => 'Quality Control',
                'code' => 'qc',
            ],
            [
                'name' => 'Analis',
                'code' => 'analis',
            ],
            [
                'name' => 'Kepala UPTD',
                'code' => 'kepala_uptd',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['code' => $role['code']],
                $role
            );
        }
    }
}
