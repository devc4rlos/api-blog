<?php

namespace App\Http\Builder;

use App\Http\Pagination\PaginatorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

interface ResponseBuilderInterface
{
    public function getMessage(): string;

    public function getCode(): int;

    public function getResult(): ?array;

    public function getWarnings(): ?array;

    public function getMetadata(): array;

    public function getPaginator(): ?PaginatorInterface;

    public function setMessage(string $message): self;

    public function setCode(int $code): self;

    public function setResult(array $result): self;

    public function setResultResource(JsonResource $resource, ?Request $request = null): self;

    public function setWarning(string $attribute, mixed $value): self;

    public function setListWarning(array $listWarning): self;

    public function setMetadata(string $attribute, mixed $value): self;

    public function setListMetadata(array $listMedata): self;

    public function setPaginator(PaginatorInterface $paginator): self;

    public function response(): JsonResponse;

    public function getDataResponse(): array;
}
