<?php

namespace Database\Factories;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'slug' => $this->faker->unique()->slug(),
            'body' => $this->faker->realText(2000),
            'image_path' => $this->faker->filePath(),
            'status' => $this->faker->randomElement(PostStatusEnum::cases())->value,
        ];
    }
}
