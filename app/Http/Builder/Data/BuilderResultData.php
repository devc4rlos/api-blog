<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

class BuilderResultData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $result = $responseBuilder->getResult();
        $dataKey = in_array($responseBuilder->getCode(), [200, 201, 204]) ? 'data' : ($result !== null ? 'errors' : 'data');

        if ($result !== null) {
            $data[$dataKey] = $result;
        }

        return parent::handle($responseBuilder, $data);
    }
}
