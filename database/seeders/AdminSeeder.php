<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    create([
    'name' => 'Admin',
    'email' => 'admin@gmail.com',
    'password' => Hash::make('admin'),
    'user_type' => 1,
    'status' => 0
]);
    }
}
