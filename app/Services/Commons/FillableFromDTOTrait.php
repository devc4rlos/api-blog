<?php

namespace App\Services\Commons;

use Illuminate\Database\Eloquent\Model;

trait FillableFromDTOTrait
{
    protected function fill(Model $entity, object $dto, array $fillableFields): object
    {
        foreach ($fillableFields as $field) {
            $getter = 'get' . ucfirst($field);

            if (method_exists($dto, $getter) && $dto->has($field)) {
                $entity->{$field} = $dto->{$getter}();
            } elseif (method_exists($dto, $field) && $dto->has($field)) {
                $entity->{$field} = $dto->{$field}();
            }
        }

        return $entity;
    }
}
