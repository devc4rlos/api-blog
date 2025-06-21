<?php

namespace Tests\Unit\Decorators\User;

use App\Decorators\User\UserCacheRepositoryDecorator;
use App\Dto\Filter\FiltersDto;
use App\Dto\User\CreateUserPersistenceDto;
use App\Dto\User\UpdateUserPersistenceDto;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class UserCacheRepositoryDecoratorTest extends TestCase
{
    private MockInterface|UserRepositoryInterface $repositoryMock;
    private MockInterface|CacheRepository $cacheRepositoryMock;
    private UserCacheRepositoryDecorator $decorator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(UserRepositoryInterface::class);
        $loggerMock = Mockery::mock(LoggerInterface::class);

        $loggerMock->shouldReceive('info');
        $loggerMock->shouldReceive('debug');

        $this->cacheRepositoryMock = Mockery::mock(CacheRepository::class);
        $cacheFactoryMock = Mockery::mock(CacheFactory::class);

        $this->cacheRepositoryMock->shouldReceive('tags')->andReturnSelf();
        $cacheFactoryMock->shouldReceive('store')->andReturn($this->cacheRepositoryMock);

        $this->decorator = new UserCacheRepositoryDecorator($this->repositoryMock, $cacheFactoryMock, $loggerMock);
    }

    public function test_should_return_user_from_cache_when_finding_by_id()
    {
        $userName = 'Test User';
        $filtersDTO = new FiltersDto();

        $this->cacheRepositoryMock
            ->shouldReceive('remember')
            ->once()
            ->andReturn(new User(['name' => $userName]));

        $this->repositoryMock->shouldNotReceive('findById');

        $result = $this->decorator->findById(1, $filtersDTO);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($userName, $result->name);
    }

    public function test_should_return_paginated_users_from_cache()
    {
        $filtersDTO = new FiltersDto();

        $paginator = Mockery::mock(LengthAwarePaginator::class);
        $this->cacheRepositoryMock
            ->shouldReceive('remember')
            ->once()
            ->andReturn($paginator);

        $this->repositoryMock->shouldNotReceive('all');

        $result = $this->decorator->all($filtersDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_should_flush_cache_and_delegate_creation_to_repository()
    {
        $dto = Mockery::mock(CreateUserPersistenceDto::class);
        $user = new User(['id' => 1]);

        $this->cacheRepositoryMock->shouldReceive('flush')->once();
        $this->repositoryMock->shouldReceive('create')->with($dto)->once()->andReturn($user);

        $result = $this->decorator->create($dto);

        $this->assertEquals($user, $result);
    }

    public function test_should_flush_cache_and_delegate_update_to_repository()
    {
        $dto = Mockery::mock(UpdateUserPersistenceDto::class)->makePartial();
        $user = new User(['id' => 1, 'name' => 'Test User']);

        $this->cacheRepositoryMock->shouldReceive('flush')->once();
        $this->repositoryMock->shouldReceive('update')->once()->andReturn(true);

        $result = $this->decorator->update($user, $dto);

        $this->assertTrue($result);
    }

    public function test_should_flush_cache_and_delegate_deletion_to_repository()
    {
        $user = new User(['id' => 1, 'name' => 'Test User']);

        $this->cacheRepositoryMock->shouldReceive('flush')->once();
        $this->repositoryMock->shouldReceive('delete')->once()->andReturn(true);

        $result = $this->decorator->delete($user);

        $this->assertTrue($result);
    }
}
