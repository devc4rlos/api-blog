<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilder;
use App\Http\Builder\ResponseBuilderInterface;
use App\Http\Pagination\PaginatorInterface;

class BuilderPaginateData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $paginator = $responseBuilder->getPaginator();

        if ($paginator) {
            $data = $this->setLinks($paginator, $data);
            $this->setMetadata($responseBuilder, $paginator);
        }

        return parent::handle($responseBuilder, $data);
    }

    private function setLinks(PaginatorInterface $paginator, array $data): array
    {
        $linksData = $data['links'] ?? [];
        $linksPagination = $paginator->links();

        $data['links'] = array_merge($linksData, $linksPagination);

        return $data;
    }

    private function setMetadata(ResponseBuilder $responseBuilder, PaginatorInterface $paginator): void
    {
        $responseBuilder->setListMetadata([
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'path' => $paginator->path(),
        ]);
    }
}
