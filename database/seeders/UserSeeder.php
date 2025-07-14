<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = bcrypt('password');

        // Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => $password,
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Miloš Ilić',
            'email' => 'milos@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name' => 'Ana Petrović',
            'email' => 'ana@gmail.com',
            'password' => $password,
        ]);
    }
}
