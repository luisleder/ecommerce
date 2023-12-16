<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'dev@mail.com')->first();
        if(!$user)
        {
            User::creating([
                'name' => 'dev',
                'email' => 'dev@mail.com',
                'password' => bcrypt("12345678")
            ]);
        }
    }
}
