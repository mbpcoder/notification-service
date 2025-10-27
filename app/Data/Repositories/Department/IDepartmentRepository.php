<?php

namespace App\Data\Repositories\Department;

use App\Data\Entities\Department;
use Illuminate\Support\Collection;

interface IDepartmentRepository
{
    public function getOneById(int $id): null|Department;

    public function getAllByIds(array $ids): Collection;

    public function create(Department $department): Department;

    public function update(Department $department): int;

}