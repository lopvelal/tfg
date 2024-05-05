<?php

namespace Database\Seeders;

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

        \App\Models\User::factory(50)->create()->each(function ($user) {
            $user->assignRole('alumno');
        });

        \App\Models\User::factory(5)->create()->each(function ($user) {
            $user->assignRole('profesor');
        });

        \App\Models\User::factory()->create([
            'name' => 'profesor',
            'email' => 'profesor@mail.com',
            'password' => Hash::make('UPM123'),
        ])->assignRole('profesor');
    }
}
