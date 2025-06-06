<?php

namespace App\Http\Builder\Data;

use App\Http\Builder\ResponseBuilderInterface;

interface BuilderDataInterface
{
    public function setNext(BuilderDataInterface $builderData): BuilderDataInterface;

    public function handle(ResponseBuilderInterface $responseBuilder, array $data): array;
}
