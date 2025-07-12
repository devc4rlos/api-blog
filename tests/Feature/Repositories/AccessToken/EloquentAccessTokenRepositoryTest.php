<?php

namespace Tests\Feature\Repositories\AccessToken;

use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use App\Repositories\AccessToken\EloquentAccessTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentAccessTokenRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_create_new_access_token()
    {
        $userCreated = User::factory()->create();
        $name = 'testing';
        $abilities = ['post:create', 'post:read'];
        $expiresAt = now()->addHour();
        $dto = new CreateAccessTokenPersistenceDto($userCreated, $name, $abilities, $expiresAt);

        $repository = new EloquentAccessTokenRepository();
        $newAccessToken = $repository->createToken($dto);
        $tokenModel = $newAccessToken->accessToken;

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $userCreated->id,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
        ]);
        $this->assertNotEmpty($newAccessToken->plainTextToken);

        $this->assertTrue($tokenModel->can('post:create'));
        $this->assertTrue($tokenModel->can('post:read'));
        $this->assertFalse($tokenModel->can('post:delete'));
    }

    public function test_should_revoke_access_token()
    {
        $userCreated = User::factory()->create();
        $userCreated->createToken('testing');
        $accessToken = $userCreated->tokens()->first();

        $repository = new EloquentAccessTokenRepository();
        $result = $repository->revokeToken($accessToken);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $accessToken->id,
        ]);
        $this->assertTrue($result);
    }

    public function test_should_get_access_token()
    {
        $keyName = 'integration-token';
        $userCreated = User::factory()->create();
        $userCreated->createToken('testing');
        $userCreated->createToken($keyName);

        $repository = new EloquentAccessTokenRepository();
        $accessToken = $repository->getLastTokenByName($userCreated, $keyName);

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $accessToken->id,
            'tokenable_type' => User::class,
        ]);
    }

    public function test_should_return_null_when_trying_to_retrieve_token_with_invalid_name()
    {
        $userCreated = User::factory()->create();
        $userCreated->createToken('testing');

        $repository = new EloquentAccessTokenRepository();
        $result = $repository->getLastTokenByName($userCreated, 'invalid-name');

        $this->assertNull($result);
    }
}
