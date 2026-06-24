<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $participants = [
            ['name' => 'Fate', 'username' => 'fate'],
            ['name' => 'Boco', 'username' => 'boco'],
            ['name' => 'David', 'username' => 'david'],
            ['name' => 'Nikola', 'username' => 'nikola'],
            ['name' => 'Pane', 'username' => 'pane'],
            ['name' => 'Gjoso', 'username' => 'gjoso'],
        ];

        foreach ($participants as $p) {
            User::create([
                'name' => $p['name'],
                'username' => $p['username'],
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'must_change_password' => true,
                'onboarding_completed' => false,
            ]);
        }

        User::create([
            'name' => 'Challenge Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'must_change_password' => false,
            'onboarding_completed' => true,
        ]);
    }
}