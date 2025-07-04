<?php

namespace Tests\Feature\Model;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    public function test_should_return_s3_temp_url_for_existing_image(): void
    {
        Storage::fake('s3');

        $post = Post::factory()->create([
            'image_path' => 'imagens/post-exemplo.jpg',
        ]);

        $imageUrl = $post->image_url;

        $this->assertNotNull($imageUrl);
        $this->assertIsString($imageUrl);
    }

    public function test_should_return_null_when_there_is_no_image(): void
    {
        $post = Post::factory()->create([
            'image_path' => null,
        ]);

        $imageUrl = $post->image_url;

        $this->assertNull($imageUrl);
    }
}
