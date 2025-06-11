<?php

namespace App\DTO\Filter;

readonly class FiltersDTO
{
    private ?string $search;
    private ?string $sortBy;
    private ?string $sortDirection;
    private ?string $relationships;

    public function __construct(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortDirection = null,
        ?string $relationships = null,
    )
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->relationships = $relationships;
    }

    public function search(): ?string
    {
        return $this->search;
    }

    public function sortBy(): ?string
    {
        return $this->sortBy;
    }

    public function sortDirection(): ?string
    {
        return $this->sortDirection;
    }

    public function relationships(): ?string
    {
        return $this->relationships;
    }
}
