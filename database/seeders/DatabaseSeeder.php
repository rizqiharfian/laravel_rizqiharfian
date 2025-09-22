<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Rizqi Harfian',
            'username' => 'rizqi', // 👈 isi username
            'email' => 'jayarayak99@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
    }
}
