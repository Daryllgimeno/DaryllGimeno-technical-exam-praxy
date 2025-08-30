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
            ['email' => 'sample@sample.com'], 
            [
                'username' => 'sample', 
                'name' => 'sample', 
                'password' => Hash::make('sample'), 
            ]
        );
    }
}
