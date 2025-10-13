<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SingleUserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dimas Fajar',
            'email' => 'dimas@example.com',
            'password' => Hash::make('password123'), // selalu hash password!
        ]);
    }
}
