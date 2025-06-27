<?php

namespace Tests\Feature\Repositories\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Models\User;
use App\Repositories\User\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieved_all_users()
    {
        User::factory()->count(10)->create();
        $repository = new EloquentUserRepository();

        $users = $repository->all(new FiltersDto());

        $this->assertEquals(User::orderBy('id')->pluck('id'), $users->pluck('id'));
    }

    public function test_should_find_user_id()
    {
        $userCreated = User::factory()->create();
        $repository = new EloquentUserRepository();

        $user = $repository->findById($userCreated->id, new FiltersDto());

        $this->assertSame($userCreated->id, $user->id);
    }

    public function test_should_create_user()
    {
        $dto = new CreateUserPersistenceDto(name: 'Test User', email: 'dev@carlosalexandre.com.br', password: '12345678');
        $repository = new EloquentUserRepository();

        $user = $repository->create($dto);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $dto->name(), 'email' => $dto->email()]);
        $this->assertFalse($user->isAdmin());
    }

    public function test_should_create_user_admin()
    {
        $dto = new CreateUserPersistenceDto(
            name: 'Test User',
            email: 'dev@carlosalexandre.com.br',
            password: '12345678',
            isAdmin: true,
        );

        $repository = new EloquentUserRepository();
        $user = $repository->create($dto);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $dto->name(), 'is_admin' => true]);
        $this->assertTrue($user->isAdmin());
    }

    public function test_should_update_user()
    {
        $changedName = 'Test user';
        $userCreated = User::factory()->create();
        $dto = new UpdateUserPersistenceDto(['name' => $changedName]);
        $repository = new EloquentUserRepository();

        $repository->update($userCreated, $dto);

        $this->assertDatabaseHas('users', ['id' => $userCreated->id, 'name' => $changedName]);
    }

    public function test_should_update_user_to_admin()
    {
        $userCreated = User::factory()->create();
        $dto = new UpdateUserPersistenceDto(['is_admin' => true]);
        $repository = new EloquentUserRepository();

        $repository->update($userCreated, $dto);

        $this->assertDatabaseHas('users', ['id' => $userCreated->id, 'is_admin' => true]);
        $this->assertTrue($userCreated->isAdmin());
    }

    public function test_should_delete_user()
    {
        $user = User::factory()->create();
        $repository = new EloquentUserRepository();

        $repository->delete($user);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_should_find_user_email()
    {
        $userCreated = User::factory()->create();
        $repository = new EloquentUserRepository();

        $user = $repository->findByEmail($userCreated->email);

        $this->assertSame($userCreated->id, $user->id);
        $this->assertSame($userCreated->email, $user->email);
    }

    public function test_should_count_admins()
    {
        $numberAdminUsers = 10;
        User::factory()->count($numberAdminUsers)->create(['is_admin' => true]);

        $repository = new EloquentUserRepository();

        $this->assertSame($numberAdminUsers, $repository->countAdmins());
    }
}
