<?php

namespace Tests\Feature\Http\Controllers\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterStandardUserControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/register';

    public static function provideDataUser(): array
    {
        $name = fake()->name();
        $email = fake()->email();
        $password = fake()->password(8);
        return [
            'Create standard user' => ['name' => $name, 'email' => $email, 'password' => $password, 'isAdmin' => false],
            'Should not be able to create standard user with admin permission' => ['name' => $name, 'email' => $email, 'password' => $password, 'isAdmin' => true],
        ];
    }

    #[DataProvider('provideDataUser')]
    public function test_should_register_standard_user(string $name, string $email, string $password, bool $isAdmin)
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
            'isAdmin' => $isAdmin,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/user.store'), $response->json('message'));
        $this->assertDatabaseHas('users', ['email' => $email, 'name' => $name, 'is_admin' => false]);
    }
}
