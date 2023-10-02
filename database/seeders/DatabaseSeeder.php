<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Ditowner',
            'username' => 'dito',
            'password' => bcrypt('12345678'),
            'role' => 'owner',
        ]);
        User::create([
            'nama' => 'Faryawan',
            'username' => 'farhan',
            'password' => bcrypt('12345678'),
            'role' => 'karyawan',
        ]);
    }
}
