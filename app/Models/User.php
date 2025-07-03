<?php

namespace App\Models;

use App\Contracts\ModelCrudInterface;
use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;
use App\Notifications\ResetPasswordNotification;
use App\Repositories\QueryPipelines\OrderByQueryPipeline;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SensitiveParameter;

class User extends Authenticatable implements ModelCrudInterface
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin ?? false;
    }

    public function sendPasswordResetNotification(#[SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token, $this->name));
    }

    public static function pipelinesFindAll(): QueryPipelinesDto
    {
        return new QueryPipelinesDto(OrderByQueryPipeline::class);
    }

    public static function pipelinesFindOne(): QueryPipelinesDto
    {
        return new QueryPipelinesDto(OrderByQueryPipeline::class);
    }

    public function allowedSortBy(): array
    {
        return ['email', 'name', 'is_admin', 'created_at'];
    }

    public function defaultSortBy(): string
    {
        return 'created_at';
    }

    public function allowedSortDirection(): array
    {
        return ['asc', 'desc'];
    }

    public function defaultSortDirection(): string
    {
        return 'desc';
    }
}
