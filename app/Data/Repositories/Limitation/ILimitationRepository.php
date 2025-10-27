<?php

namespace App\Data\Repositories\Limitation;

use App\Data\Entities\Limitation;

interface ILimitationRepository
{
    public function getOneActiveByDepartmentId(int $departmentId): Limitation|null;

    public function getOneById(int $id): null|Limitation;

    public function create(Limitation $limitation): Limitation;

    public function update(Limitation $limitation): int;

}
