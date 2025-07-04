<?php

namespace Tests\Feature\Model;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_convert_the_status_to_the_correct_enum(): void
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        $status = $post->status;

        self::assertInstanceOf(PostStatusEnum::class, $status);
        self::assertEquals(PostStatusEnum::PUBLISHED, $status);
    }
}
