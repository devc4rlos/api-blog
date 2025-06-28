<?php

namespace Database\Factories;

use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasswordResetFactory extends Factory
{
    protected $model = PasswordReset::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'token' => bcrypt(Str::random(6)),
            'created_at' => now(),
        ];
    }
}
