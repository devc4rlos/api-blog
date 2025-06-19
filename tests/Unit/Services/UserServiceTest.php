<?php

namespace Tests\Unit\Services;

use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserInputDTO;
use App\DTO\User\UpdateUserInputDTO;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private MockInterface&UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(UserRepositoryInterface::class);
    }

    public function test_should_return_all_users()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $filtersDTO = new FiltersDTO();

        $this->repository->shouldReceive('all')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new UserService($this->repository);
        $service->all($filtersDTO);
    }

    public function test_should_return_user_by_id()
    {
        $user = Mockery::mock(User::class);
        $filtersDTO = new FiltersDTO();

        $this->repository->shouldReceive('findById')
            ->andReturn($user)
            ->once();

        $service = new UserService($this->repository);
        $service->findById(1, $filtersDTO);
    }

    public function test_should_create_user()
    {
        $user = Mockery::mock(User::class);

        $dto = Mockery::mock(CreateUserInputDTO::class);
        $dto->shouldReceive('name')->once()->andReturn('name');
        $dto->shouldReceive('email')->once()->andReturn('email');
        $dto->shouldReceive('password')->once()->andReturn('password');

        $this->repository->shouldReceive('create')
            ->andReturn($user)
            ->once();
        $service = new UserService($this->repository);
        $service->create($dto);
    }

    public function test_should_update_user()
    {
        $user = Mockery::mock(User::class);
        $dto = Mockery::mock(UpdateUserInputDTO::class);

        $dto->shouldReceive('has')->with('name')->once()->andReturn(true);
        $dto->shouldReceive('has')->with('email')->once()->andReturn(true);
        $dto->shouldReceive('name')->once()->andReturn('name');
        $dto->shouldReceive('email')->once()->andReturn('email');

        $this->repository->shouldReceive('update')
            ->andReturn(true)
            ->once();
        $service = new UserService($this->repository);
        $service->update($user, $dto);
    }

    public function test_should_delete_user()
    {
        $user = Mockery::mock(User::class);

        $this->repository->shouldReceive('delete')
            ->andReturn(true)
            ->once();

        $service = new UserService($this->repository);
        $service->delete($user);
    }

    public function test_should_return_user_by_email()
    {
        $user = Mockery::mock(User::class);
        $this->repository->shouldReceive('findByEmail')->once()->andReturn($user);

        $service = new UserService($this->repository);
        $userReturned = $service->findByEmail(fake()->email());

        $this->assertEquals($user, $userReturned);
    }
}
