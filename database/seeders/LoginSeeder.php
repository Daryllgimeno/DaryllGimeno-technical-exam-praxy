<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], 
            [
                'username' => 'admin', 
                'name' => 'admin', 
                'password' => Hash::make('admin'), 
            ]
        );
    }
}
