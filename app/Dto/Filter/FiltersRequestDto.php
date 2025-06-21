<?php

namespace App\Dto\Filter;

use Illuminate\Http\Request;

class FiltersRequestDto extends FiltersDto
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
