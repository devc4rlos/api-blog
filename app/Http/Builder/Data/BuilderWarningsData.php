<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

class BuilderWarningsData extends BuilderData
{
    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        $warnings = $responseBuilder->getWarnings();

        if ($warnings !== null) {
            $data['warnings'] = $warnings;
        }

        return parent::handle($responseBuilder, $data);
    }
}
