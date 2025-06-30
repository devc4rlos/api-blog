<?php

namespace Tests\Unit\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\User\CreateUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Exceptions\BusinessRuleException;
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
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('all')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new UserService($this->repository);
        $service->all($filtersDTO);
    }

    public function test_should_return_user_by_id()
    {
        $user = Mockery::mock(User::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('findById')
            ->andReturn($user)
            ->once();

        $service = new UserService($this->repository);
        $service->findById(1, $filtersDTO);
    }

    public function test_should_create_user()
    {
        $user = Mockery::mock(User::class);

        $dto = Mockery::mock(CreateUserInputDto::class);
        $dto->shouldReceive('name')->once()->andReturn('name');
        $dto->shouldReceive('email')->once()->andReturn('email');
        $dto->shouldReceive('password')->once()->andReturn('password');
        $dto->shouldReceive('isAdmin')->once()->andReturn(false);

        $this->repository->shouldReceive('create')
            ->andReturn($user)
            ->once();
        $service = new UserService($this->repository);
        $service->create($dto);
    }

    public function test_should_create_standard_user()
    {
        $user = Mockery::mock(User::class);

        $dto = Mockery::mock(CreateUserInputDto::class);
        $dto->shouldReceive('name')->once()->andReturn('name');
        $dto->shouldReceive('email')->once()->andReturn('email');
        $dto->shouldReceive('password')->once()->andReturn('password');

        $this->repository->shouldReceive('create')
            ->andReturn($user)
            ->once();
        $service = new UserService($this->repository);
        $service->createStandardUser($dto);
    }

    public function test_should_update_user()
    {
        $user = Mockery::mock(User::class);
        $dto = Mockery::mock(UpdateUserInputDto::class);
        $dto->shouldReceive('toArray')->once()->andReturn([]);

        $this->repository->shouldReceive('update')
            ->andReturn(true)
            ->once();
        $service = new UserService($this->repository);
        $service->update($user, $dto);
    }

    public function test_should_update_password_user()
    {
        $user = Mockery::mock(User::class);
        $password = fake()->password(8);

        $this->repository->shouldReceive('update')
            ->andReturn(true)
            ->once();
        $service = new UserService($this->repository);
        $service->updatePassword($user, $password);
    }

    /**
     * @throws BusinessRuleException
     */
    public function test_should_delete_user()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('isAdmin')->once()->andReturn(false);

        $this->repository->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $service = new UserService($this->repository);
        $service->delete($user);
    }

    public function test_should_throw_exception_business_rule_when_trying_to_remove_the_last_admin()
    {
        $this->expectException(BusinessRuleException::class);
        $user = Mockery::mock(User::class);
        $user->shouldReceive('isAdmin')->once()->andReturn(true);

        $this->repository->shouldReceive('countAdmins')->once()->andReturn(1);

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
