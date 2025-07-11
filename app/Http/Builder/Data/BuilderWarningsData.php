<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

class BuilderWarningsData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $warnings = $responseBuilder->getWarnings();

        if (count($warnings) > 0) {
            $data['warnings'] = $warnings;
        }

        return parent::handle($responseBuilder, $data);
    }
}
