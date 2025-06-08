<?php

namespace App\Http\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorLengthAwarePaginator implements PaginatorInterface
{
    private LengthAwarePaginator $paginator;
    private array $queryParameters;

    public function __construct(LengthAwarePaginator $paginator, array $queryParameters = [])
    {
        $this->paginator = $paginator;
        $this->queryParameters = $queryParameters;
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function path(): ?string
    {
        return $this->paginator->path();
    }

    public function linkFirst(): string
    {
        return $this->paginator->appends($this->queryParameters)->url(1);
    }

    public function linkLast(): string
    {
        return $this->paginator->appends($this->queryParameters)->url($this->paginator->lastPage());
    }

    public function linkPrevious(): ?string
    {
        return $this->paginator->appends($this->queryParameters)->previousPageUrl();
    }

    public function linkNext(): ?string
    {
        return $this->paginator->appends($this->queryParameters)->nextPageUrl();
    }

    public function links(): array
    {
        return [
            'first' => $this->linkFirst(),
            'last' => $this->linkLast(),
            'prev' => $this->linkPrevious(),
            'next' => $this->linkNext(),
        ];
    }
}
