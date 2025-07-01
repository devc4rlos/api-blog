<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/account/';
    private User $userAdmin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userAdmin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    public function test_admin_can_view_own_account(): void
    {
        $response = $this->actingAs($this->userAdmin)->get($this->endpoint);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $this->userAdmin->id,
                'name' => $this->userAdmin->name,
                'email' => $this->userAdmin->email,
                'is_admin' => $this->userAdmin->is_admin,
            ]
        ]);
    }

    public function test_standard_user_can_view_own_account(): void
    {
        $response = $this->actingAs($this->user)->get($this->endpoint);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]
        ]);
        $response->assertJsonMissingPath('data.is_admin');
    }

    public function test_admin_can_update_own_account(): void
    {
        $newName = fake()->name();

        $response = $this->actingAs($this->userAdmin)->patch($this->endpoint, [
            'name' => $newName,
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $this->userAdmin->id,
                'name' => $newName,
                'email' => $this->userAdmin->email,
                'is_admin' => $this->userAdmin->is_admin,
            ]
        ]);
    }

    public function test_standard_user_can_update_own_account(): void
    {
        $newName = fake()->name();

        $response = $this->actingAs($this->user)->patch($this->endpoint, [
            'name' => $newName,
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $this->user->id,
                'name' => $newName,
                'email' => $this->user->email,
            ]
        ]);
        $response->assertJsonMissingPath('data.is_admin');
    }

    public function test_standard_user_cannot_set_themselves_as_admin(): void
    {
        $newName = fake()->name();

        $response = $this->actingAs($this->user)->patch($this->endpoint, [
            'name' => $newName,
            'is_admin' => true,
        ]);

        $response->assertOk();
        $response->assertJsonMissingPath('data.is_admin');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'is_admin' => false,
        ]);
    }

    public function test_admin_can_delete_own_account_if_other_admins_exist(): void
    {
        User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($this->userAdmin)->delete($this->endpoint);

        $response->assertOk();
        $this->assertDatabaseMissing($this->userAdmin);
    }

    public function test_it_cannot_delete_account_if_it_is_the_last_admin(): void
    {
        $response = $this->actingAs($this->userAdmin)->delete($this->endpoint);

        $response->assertStatus(403);

        $this->assertDatabaseHas($this->userAdmin);
    }
}
