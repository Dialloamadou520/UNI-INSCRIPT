<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@universite.sn'],
            [
                'name' => 'Administrateur',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}
