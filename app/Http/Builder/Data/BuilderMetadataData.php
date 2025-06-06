<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

class BuilderMetadataData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $metadata = $responseBuilder->getMetadata();

        if (count($metadata) > 0) {
            $data['meta'] = $metadata;
        }

        return parent::handle($responseBuilder, $data);
    }
}
