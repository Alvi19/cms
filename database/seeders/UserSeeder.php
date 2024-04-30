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
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '089699759595',
                'password' => Hash::make('admin12345')
            ],
        ];

        // Looping and Inserting Array's Users into User Table
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
