<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(WorkSeeder::class);

        User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'username' => 'admin',
            'password' => bcrypt('12345678')
        ]);
    }
}
