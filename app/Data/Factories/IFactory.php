<?php

namespace App\Data\Factories;

use App\Data\Entities\Entity;
use Illuminate\Support\Collection;
use stdClass;

interface IFactory
{
    public function makeEntityFromStdClass(stdClass $entity): Entity;

    public function makeCollectionOfEntities(Collection|array $entities): Collection;
}
