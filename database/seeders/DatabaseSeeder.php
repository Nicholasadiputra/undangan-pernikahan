<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@example.com', // Kolom email wajib diisi karena file migrasi asli (0001_01_01_000000_create_users_table.php) mensyaratkannya
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }
}