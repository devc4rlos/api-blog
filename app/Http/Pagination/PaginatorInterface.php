<?php

namespace App\Http\Pagination;

interface PaginatorInterface
{
    public function total(): int;
    public function perPage(): int;
    public function currentPage(): int;
    public function lastPage(): int;
    public function path(): ?string;
    public function linkFirst(): string;
    public function linkLast(): string;
    public function linkPrevious(): string;
    public function linkNext(): string;
    public function links(): array;
}
