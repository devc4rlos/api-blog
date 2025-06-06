<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

abstract class BuilderData implements BuilderDataInterface
{
    private ?BuilderDataInterface $nextHandler = null;

    public function setNext(BuilderDataInterface $builderData): BuilderDataInterface
    {
        $this->nextHandler = $builderData;
        return $builderData;
    }

    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array
    {
        if ($this->nextHandler === null) {
            return $data;
        }

        return $this->nextHandler->handle($responseBuilder, $data);
    }
}
