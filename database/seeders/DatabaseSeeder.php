<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => 'test123',
        // ]);

    User::create([
        'name' => 'New Admin',
        'email' => 'newadmin@example.com',
        'password' => Hash::make('newpassword'), // Pastikan password di-hash
        'role' => 'admin',
    ]);
        }
}
