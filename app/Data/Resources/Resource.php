<?php

namespace App\Data\Resources;

use App\Data\Entities\Entity;
use Illuminate\Support\Collection;

abstract class Resource
{
    abstract public function toArray(Entity $entity): array;

    public function collectionToArray(Collection $entities): array
    {
        $entityArray = [];

        foreach ($entities as $_entity) {
            $entityArray[] = $this->toArray($_entity);
        }

        return $entityArray;
    }

    public function collectionToArrayWithForeignKeys(Collection $entities): array
    {
        $entityArray = [];

        foreach ($entities as $_entity) {
            $entityArray[] = $this->toArrayWithForeignKeys($_entity);
        }

        return $entityArray;
    }

    public function toArrayWithForeignKeys($sms): array
    {
        return $this->toArray($sms) + [];
    }
}
