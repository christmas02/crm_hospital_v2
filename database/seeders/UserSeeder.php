<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Administrateur',
                'email' => 'admin@medicare.ci',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Réceptionniste',
                'email' => 'reception@medicare.ci',
                'password' => Hash::make('password'),
                'role' => 'reception',
            ],
            [
                'name' => 'Dr. Yao Kouadio',
                'email' => 'medecin@medicare.ci',
                'password' => Hash::make('password'),
                'role' => 'medecin',
            ],
            [
                'name' => 'Caissière',
                'email' => 'caisse@medicare.ci',
                'password' => Hash::make('password'),
                'role' => 'caisse',
            ],
            [
                'name' => 'Pharmacien',
                'email' => 'pharmacie@medicare.ci',
                'password' => Hash::make('password'),
                'role' => 'pharmacie',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
