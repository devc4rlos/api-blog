<?php

namespace App\DTO\Filter;

use Illuminate\Http\Request;

class FiltersRequestDTO extends FiltersDTO
{
    public function __construct(
        Request $request
    )
    {
        parent::__construct(
            search: $request->get('search'),
            sortBy: $request->get('sortBy'),
            sortDirection: $request->get('sortDirection'),
            relationships: $request->get('relationships'),
            page: $request->get('page'),
        );
    }
}
