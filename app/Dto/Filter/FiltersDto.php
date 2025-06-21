<?php

namespace App\Dto\Filter;

class FiltersDto
{
    private ?string $search;
    private ?string $sortBy;
    private ?string $sortDirection;
    private ?string $relationships;
    private ?string $page;

    public function __construct(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortDirection = null,
        ?string $relationships = null,
        ?string $page = null,
    )
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->relationships = $relationships;
        $this->page = $page;
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

    public function page(): ?string
    {
        return $this->page;
    }
}
