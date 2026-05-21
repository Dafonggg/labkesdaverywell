<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions, and role-permission mappings
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Define default users for each role
        $users = [
            [
                'role_code' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@labkesda.go.id',
                'password' => 'password',
            ],
            [
                'role_code' => 'petugas_lapangan',
                'name' => 'Petugas Lapangan',
                'email' => 'petugas_lapangan@labkesda.go.id',
                'password' => 'password',
            ],
            [
                'role_code' => 'petugas_lab',
                'name' => 'Petugas Lab',
                'email' => 'petugas_lab@labkesda.go.id',
                'password' => 'password',
            ],
            [
                'role_code' => 'qc',
                'name' => 'Quality Control',
                'email' => 'qc@labkesda.go.id',
                'password' => 'password',
            ],
            [
                'role_code' => 'analis',
                'name' => 'Analis',
                'email' => 'analis@labkesda.go.id',
                'password' => 'password',
            ],
            [
                'role_code' => 'kepala_uptd',
                'name' => 'Kepala UPTD',
                'email' => 'kepala_uptd@labkesda.go.id',
                'password' => 'password',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('code', $userData['role_code'])->first();

            if ($role) {
                User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'role_id' => $role->id,
                        'name' => $userData['name'],
                        'password' => $userData['password'],
                        'phone' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
