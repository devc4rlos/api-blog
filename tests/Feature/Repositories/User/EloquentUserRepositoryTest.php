<?php

namespace Feature\Repositories\User;

use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserPersistenceDTO;
use App\DTO\User\UpdateUserPersistenceDTO;
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

        $users = $repository->all(new FiltersDTO());

        $this->assertEquals(User::orderBy('id')->pluck('id'), $users->pluck('id'));
    }

    public function test_should_find_user()
    {
        $userCreated = User::factory()->create();
        $repository = new EloquentUserRepository();

        $user = $repository->findById($userCreated->id, new FiltersDTO());

        $this->assertSame($userCreated->id, $user->id);
    }

    public function test_should_create_user()
    {
        $dto = new CreateUserPersistenceDTO(name: 'Test User', email: 'dev@carlosalexandre.com.br', password: '12345678');
        $repository = new EloquentUserRepository();

        $user = $repository->create($dto);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $dto->name, 'email' => $dto->email]);
    }

    public function test_should_update_user()
    {
        $changedName = 'Test user';
        $userCreated = User::factory()->create();
        $dto = new UpdateUserPersistenceDTO(['name' => $changedName]);
        $repository = new EloquentUserRepository();

        $repository->update($userCreated, $dto);

        $this->assertDatabaseHas('users', ['id' => $userCreated->id, 'name' => $changedName]);
    }

    public function test_should_delete_user()
    {
        $user = User::factory()->create();
        $repository = new EloquentUserRepository();

        $repository->delete($user);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
