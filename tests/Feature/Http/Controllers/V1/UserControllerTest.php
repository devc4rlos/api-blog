<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/admin/users/';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($this->user);
    }

    public static function provideFilters(): array
    {
        return [
            'Default' => ['', 'created_at', 'desc'],
            'Sort by invalid' => ['?sortBy=invalid', 'created_at', 'desc'],
            'Sort direction invalid' => ['?sortDirection=invalid', 'created_at', 'desc'],
            'Sort by and sort direction' => ['?sortBy=name&sortDirection=asc', 'name', 'asc'],
        ];
    }

    public function test_should_retrieved_all_users()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.index'), $response->json('message'));
    }

    #[DataProvider('provideFilters')]
    public function test_should_retrieved_all_users__with_filters(string $query, string $sortBy, string $sortDirection)
    {
        User::factory()
            ->count(10)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['created_at' => now()->subMinutes($sequence->index)],
            ))
            ->create();

        $response = $this->get($this->endpoint . $query);

        $retrievedIdUsers = collect($response->json('data'))->pluck('id')->values()->toArray();
        $retrievedCreatedIdUsers = User::orderBy($sortBy, $sortDirection)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdUsers, $retrievedIdUsers);
    }

    public function test_should_retrieved_all_users_with_filter_search()
    {
        $name = 'Testing';

        $user = User::factory()->create(['name' => $name]);

        $response = $this->get($this->endpoint . '?searchBy=name&search=' . $name);

        $retrievedIdUsers = collect($response->json('data'))->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.index'), $response->json('message'));
        $this->assertTrue(in_array($user->id, $retrievedIdUsers));
    }

    public function test_should_retrieved_one_user()
    {
        $user = User::factory()->create();
        $response = $this->get($this->endpoint . $user->id);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.show'), $response->json('message'));
    }

    public function test_should_create_new_user()
    {
        $password = fake()->password(8);
        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/user.store'), $response->json('message'));
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'name' => $data['name']]);
    }

    public function test_should_create_new_user_admin()
    {
        $password = fake()->password(8);
        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'password_confirmation' => $password,
            'is_admin' => true,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/user.store'), $response->json('message'));
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'name' => $data['name'], 'is_admin' => true]);
    }

    public function test_should_update_existing_user()
    {
        $user = User::factory()->create();
        $name = 'Test User';
        $data = [
            'name' => $name,
            'email' => fake()->email(),
        ];
        $response = $this->put($this->endpoint . $user->id, $data);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.update'), $response->json('message'));
    }

    public function test_should_update_user_to_admin()
    {
        $user = User::factory()->create();
        $name = 'Test User';
        $data = [
            'name' => $name,
            'email' => fake()->email(),
            'is_admin' => true,
        ];
        $response = $this->put($this->endpoint . $user->id, $data);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.update'), $response->json('message'));
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'name' => $data['name'], 'is_admin' => true]);
    }

    public function test_should_delete_existing_user()
    {
        $user = User::factory()->create();
        $response = $this->delete($this->endpoint . $user->id);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/user.destroy'), $response->json('message'));
    }

    public function test_should_not_delete_the_last_administrator()
    {
        $response = $this->delete($this->endpoint . $this->user->id);
        $response->assertStatus(403);
    }

    public function test_should_return_404_status_when_trying_to_delete_non_existent_user()
    {
        $response = $this->delete($this->endpoint . '9999');
        $response->assertStatus(404);
    }

    public function test_should_return_404_status_when_trying_show_non_existent_user()
    {
        $response = $this->get($this->endpoint . '9999');
        $response->assertStatus(404);
    }
}
