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

        User::factory()->create([
            'username' => 'kucingscript',
            'name' => 'Ar Rasyid Sarifullah',
            'email' => 'kucingscript@gmail.com',
            'password' => Hash::make('kucingmeow'),
            'email_verified_at' => now(),
        ]);
    }
}
