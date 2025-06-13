<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3 megerősített user Test névvel kereséshez
        User::create([
            'name' => 'Test User1',
            'email' => 'teszt1@google.com',
            'password' => Hash::make('123abcde'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Test User2',
            'email' => 'teszt2@google.com',
            'password' => Hash::make('123abcde'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Test User3',
            'email' => 'teszt3@google.com',
            'password' => Hash::make('123abcde'),
            'email_verified_at' => now(),
        ]);

        User::factory()->count(10)->create([
            'email_verified_at' => now(),
        ]);
    }
}
