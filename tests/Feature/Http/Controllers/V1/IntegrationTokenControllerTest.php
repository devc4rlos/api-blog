<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IntegrationTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/admin/integration-token';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($this->user);
    }

    public function test_should_retrieve_active_integration_token_details()
    {
        $this->user->createToken('integration-token');

        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'has_active_token' => true,
            ]
        ]);
    }

    public function test_should_return_that_no_active_token_exists_for_users_without_the_integration_token()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'has_active_token' => false,
            ]
        ]);
    }

    public function test_should_create_integration_token_for_authenticated_user()
    {
        $response = $this->post($this->endpoint);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'token',
                'created_at',
            ]
        ]);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => 'integration-token',
        ]);
    }

    public function test_should_return_status_409_when_creating_new_integration_token_for_admin_with_active_token()
    {
        $this->user->createToken('integration-token');

        $response = $this->post($this->endpoint);

        $response->assertStatus(409);
    }

    public function test_should_delete_active_integration_token()
    {
        $this->user->createToken('integration-token');
        $response = $this->delete($this->endpoint);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => 'integration-token',
        ]);
    }

    public function test_should_return_status_200_even_without_active_integration_token()
    {
        $response = $this->delete($this->endpoint);
        $response->assertStatus(200);
    }
}
