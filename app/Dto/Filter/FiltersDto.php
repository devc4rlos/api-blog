<?php

namespace App\Dto\Filter;

class FiltersDto
{
    private ?string $search;
    private ?string $sortBy;
    private ?string $sortDirection;
    private ?string $page;
    private ?string $searchBy;

    public function __construct(
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortDirection = null,
        ?string $page = null,
        ?string $searchBy = null,
    )
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->page = $page;
        $this->searchBy = $searchBy;
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

    public function page(): ?string
    {
        return $this->page;
    }

    public function searchBy(): ?string
    {
        return $this->searchBy;
    }
}
