<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with demo accounts.
     */
    public function run(): void
    {
        // 1. Akun Guru (Pembina / Pembuat Event / Reviewer Logistik)
        User::firstOrCreate(
            ['username' => '198501012010011001'],
            [
                'full_name'  => 'Dra. Nur Indah, M.Pd.',
                'password'   => Hash::make('password123'),
                'role'       => 'guru',
                'class_name' => 'Koordinator Lab Banquet',
                'phone'      => '081234567890',
                'is_active'  => true,
            ]
        );

        // 2. Akun Siswa (Operasional Event & Logistik)
        User::firstOrCreate(
            ['username' => '212210045'],
            [
                'full_name'  => 'Ahmad Fauzi',
                'password'   => Hash::make('password123'),
                'role'       => 'siswa',
                'class_name' => 'XII Perhotelan 1',
                'phone'      => '089876543210',
                'is_active'  => true,
            ]
        );
        // 3. Data Dummy Event, Institusi, dan Layout Ruangan
        $this->call(EventSeeder::class);
    }
}
