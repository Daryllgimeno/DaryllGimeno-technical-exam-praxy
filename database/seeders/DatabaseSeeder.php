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
        // Default Test User
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',  
            'email' => 'test@example.com',
            'password' => bcrypt('password'), 
        ]);

        // login seeder
        $this->call([
            LoginSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
