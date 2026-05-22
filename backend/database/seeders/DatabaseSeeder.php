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

        // Seed default parameter_uji
        $parameters = [
            // ═══════════════════════════════════════════════════════
            // 1. AIR — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'Suhu', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => 30.0, 'metode_uji' => 'Termometer', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'pH', 'satuan' => '-', 'baku_mutu_min' => 6.5, 'baku_mutu_max' => 8.5, 'metode_uji' => 'pH Meter', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'DO', 'satuan' => 'mg/L', 'baku_mutu_min' => 4.0, 'baku_mutu_max' => null, 'metode_uji' => 'DO Meter', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'TDS', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 500.0, 'metode_uji' => 'TDS Meter', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Conductivity', 'satuan' => 'µS/cm', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Conductivity Meter', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Salinitas', 'satuan' => '‰', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Refraktometer', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kekeruhan', 'satuan' => 'NTU', 'baku_mutu_min' => null, 'baku_mutu_max' => 5.0, 'metode_uji' => 'Turbidimeter', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Warna', 'satuan' => 'TCU', 'baku_mutu_min' => null, 'baku_mutu_max' => 15.0, 'metode_uji' => 'Visual / Kolorimetri', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Bau', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Organoleptik', 'kategori' => 'Air', 'tipe_pengujian' => 'on_site'],

            // AIR — Laboratorium
            ['nama_parameter' => 'BOD', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 2.0, 'metode_uji' => 'Metode Winkler', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'COD', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 10.0, 'metode_uji' => 'Spektrofotometri / Refluks', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Nitrat', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 10.0, 'metode_uji' => 'Spektrofotometri', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Nitrit', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 1.0, 'metode_uji' => 'Spektrofotometri', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Amonia', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.5, 'metode_uji' => 'Fenat / Nessler', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Fosfat', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.2, 'metode_uji' => 'Spektrofotometri', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Besi (Fe)', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.3, 'metode_uji' => 'AAS', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Mangan (Mn)', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.1, 'metode_uji' => 'AAS', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Timbal (Pb)', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.01, 'metode_uji' => 'AAS', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Merkuri (Hg)', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.001, 'metode_uji' => 'AAS', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Total Coliform', 'satuan' => 'CFU/100mL', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Membrane Filter', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'E.coli', 'satuan' => 'CFU/100mL', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'MPN / Plate', 'kategori' => 'Air', 'tipe_pengujian' => 'laboratorium'],

            // ═══════════════════════════════════════════════════════
            // 2. AIR LIMBAH — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'pH', 'satuan' => '-', 'baku_mutu_min' => 6.0, 'baku_mutu_max' => 9.0, 'metode_uji' => 'pH Meter', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Suhu', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => 38.0, 'metode_uji' => 'Termometer', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Debit aliran', 'satuan' => 'L/s', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Flow Meter', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Warna', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Visual', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Bau', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Organoleptik', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'DO', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'DO Meter', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'on_site'],

            // AIR LIMBAH — Laboratorium
            ['nama_parameter' => 'BOD', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 30.0, 'metode_uji' => 'Metode Winkler', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'COD', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 100.0, 'metode_uji' => 'Spektrofotometri / Refluks', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'TSS', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 50.0, 'metode_uji' => 'Gravimetri', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Minyak & Lemak', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 5.0, 'metode_uji' => 'Ekstraksi Gravimetri', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Amonia', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.5, 'metode_uji' => 'Fenat', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Sulfida', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.05, 'metode_uji' => 'Spektrofotometri', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Detergen', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => 5.0, 'metode_uji' => 'Spektrofotometri MBAS', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Logam berat', 'satuan' => 'mg/L', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'AAS / ICP-OES', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Total Coliform', 'satuan' => 'CFU/100mL', 'baku_mutu_min' => null, 'baku_mutu_max' => 3000.0, 'metode_uji' => 'Membrane Filter', 'kategori' => 'Air Limbah', 'tipe_pengujian' => 'laboratorium'],

            // ═══════════════════════════════════════════════════════
            // 3. MAKANAN & MINUMAN — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'Suhu makanan', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Termometer Tusuk', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kondisi fisik', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Visual', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Warna', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Visual', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Bau', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Organoleptik', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'pH', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'pH Meter', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Rapid test formalin', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Test Kit Formalin', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Rapid test boraks', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Test Kit Boraks', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'on_site'],

            // MAKANAN & MINUMAN — Laboratorium
            ['nama_parameter' => 'Formalin', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Kualitatif (Test Kit)', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Boraks', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Kualitatif (Test Kit)', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Pewarna sintetis', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Kualitatif (Kromatografi)', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Pengawet', 'satuan' => 'mg/kg', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'HPLC / Spektrofotometri', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Total Plate Count', 'satuan' => 'CFU/g', 'baku_mutu_min' => null, 'baku_mutu_max' => 10000.0, 'metode_uji' => 'Cawan Sebar', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Salmonella', 'satuan' => 'per 25g', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Kualitatif (Enrichment)', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'E.coli', 'satuan' => 'CFU/g', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'MPN / Plate', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Staphylococcus', 'satuan' => 'CFU/g', 'baku_mutu_min' => null, 'baku_mutu_max' => 100.0, 'metode_uji' => 'Cawan Sebar', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Kapang & Jamur', 'satuan' => 'CFU/g', 'baku_mutu_min' => null, 'baku_mutu_max' => 1000.0, 'metode_uji' => 'Cawan Sebar', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Kadar air', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Gravimetri Oven', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Protein', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Kjeldahl', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Lemak', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Soxhlet', 'kategori' => 'Makanan & Minuman', 'tipe_pengujian' => 'laboratorium'],

            // ═══════════════════════════════════════════════════════
            // 4. UDARA — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'Suhu udara', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Termometer / Hygrometer', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kelembaban', 'satuan' => '%RH', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Hygrometer', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'PM2.5', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => 55.0, 'metode_uji' => 'Dust Sampler / BAM', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'PM10', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => 75.0, 'metode_uji' => 'Gravimetri / BAM', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'CO', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => 10000.0, 'metode_uji' => 'NDIR', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'SO2', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => 150.0, 'metode_uji' => 'Pararosanilin', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'NO2', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => 200.0, 'metode_uji' => 'Griess-Saltzman', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kebisingan', 'satuan' => 'dB(A)', 'baku_mutu_min' => null, 'baku_mutu_max' => 70.0, 'metode_uji' => 'Sound Level Meter', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Intensitas cahaya', 'satuan' => 'Lux', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Lux Meter', 'kategori' => 'Udara', 'tipe_pengujian' => 'on_site'],

            // UDARA — Laboratorium
            ['nama_parameter' => 'Logam berat udara', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'AAS / ICP-OES', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'VOC', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'GC-MS', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Formaldehida', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Spektrofotometri', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Asbes', 'satuan' => 'f/cc', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'PCM / TEM', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Silika', 'satuan' => 'mg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Gravimetri / XRD', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Total bakteri udara', 'satuan' => 'CFU/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Impinger / Slit Sampler', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Jamur udara', 'satuan' => 'CFU/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Slit Sampler', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Partikulat detail', 'satuan' => 'µg/m³', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Gravimetri HVS', 'kategori' => 'Udara', 'tipe_pengujian' => 'laboratorium'],

            // ═══════════════════════════════════════════════════════
            // 5. TANAH — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'pH tanah', 'satuan' => '-', 'baku_mutu_min' => 6.0, 'baku_mutu_max' => 8.0, 'metode_uji' => 'pH Meter 1:5 Ekstrak', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Suhu tanah', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Termometer Tanah', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kelembaban tanah', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Soil Moisture Meter', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Warna tanah', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Munsell Soil Color Chart', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Tekstur tanah', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Uji Tekstur Lapangan', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Conductivity', 'satuan' => 'µS/cm', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'EC Meter', 'kategori' => 'Tanah', 'tipe_pengujian' => 'on_site'],

            // TANAH — Laboratorium
            ['nama_parameter' => 'Nitrogen (N)', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.5, 'metode_uji' => 'Kjeldahl', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Fosfor (P)', 'satuan' => 'ppm', 'baku_mutu_min' => null, 'baku_mutu_max' => 15.0, 'metode_uji' => 'Bray & Kurtz / Olsen', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Kalium (K)', 'satuan' => 'me/100g', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Amonium Asetat 1N', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'C-Organik', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Walkley & Black', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Bahan organik', 'satuan' => '%', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Loss on Ignition', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'KTK', 'satuan' => 'me/100g', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Amonium Asetat 1N', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Timbal (Pb)', 'satuan' => 'mg/kg', 'baku_mutu_min' => null, 'baku_mutu_max' => 300.0, 'metode_uji' => 'AAS / ICP-OES', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Merkuri (Hg)', 'satuan' => 'mg/kg', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.8, 'metode_uji' => 'AAS', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Kadmium (Cd)', 'satuan' => 'mg/kg', 'baku_mutu_min' => null, 'baku_mutu_max' => 1.0, 'metode_uji' => 'AAS', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Arsenik (As)', 'satuan' => 'mg/kg', 'baku_mutu_min' => null, 'baku_mutu_max' => 12.0, 'metode_uji' => 'AAS', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Total mikroba tanah', 'satuan' => 'CFU/g', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Cawan Sebar', 'kategori' => 'Tanah', 'tipe_pengujian' => 'laboratorium'],

            // ═══════════════════════════════════════════════════════
            // 6. SWAB LINGKUNGAN — On Site
            // ═══════════════════════════════════════════════════════
            ['nama_parameter' => 'Suhu ruangan', 'satuan' => '°C', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Termometer', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kelembaban ruangan', 'satuan' => '%RH', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Hygrometer', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'Kondisi kebersihan', 'satuan' => '-', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Visual / Checklist', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'on_site'],
            ['nama_parameter' => 'ATP test', 'satuan' => 'RLU', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'ATP Luminometer', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'on_site'],

            // SWAB LINGKUNGAN — Laboratorium
            ['nama_parameter' => 'Total bakteri', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => 100.0, 'metode_uji' => 'Cawan Tuang', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'E.coli', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'MPN / Plate', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Coliform', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'MPN / Plate', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Jamur', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Cawan Sebar PDA', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Kapang', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => null, 'metode_uji' => 'Cawan Sebar PDA', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Staphylococcus', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => 0.0, 'metode_uji' => 'Cawan Sebar MSA', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
            ['nama_parameter' => 'Angka kuman', 'satuan' => 'CFU/cm²', 'baku_mutu_min' => null, 'baku_mutu_max' => 100.0, 'metode_uji' => 'Cawan Tuang', 'kategori' => 'Swab Lingkungan', 'tipe_pengujian' => 'laboratorium'],
        ];

        foreach ($parameters as $param) {
            \App\Models\ParameterUji::firstOrCreate(
                [
                    'nama_parameter' => $param['nama_parameter'],
                    'kategori' => $param['kategori'],
                ],
                [
                    'satuan' => $param['satuan'],
                    'baku_mutu_min' => $param['baku_mutu_min'],
                    'baku_mutu_max' => $param['baku_mutu_max'],
                    'metode_uji' => $param['metode_uji'],
                    'tipe_pengujian' => $param['tipe_pengujian'],
                    'is_active' => true,
                ]
            );
        }
    }
}
