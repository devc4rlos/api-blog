<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => config('settings.user.name'),
            'email' => config('settings.user.email'),
            'password' => Hash::make(config('settings.user.password')),
            'is_admin' => true,
        ]);
    }
}
