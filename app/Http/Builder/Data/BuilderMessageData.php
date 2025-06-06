<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

class BuilderMessageData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $data['message'] = $responseBuilder->getMessage();

        return parent::handle($responseBuilder, $data);
    }
}
