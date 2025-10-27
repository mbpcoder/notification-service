<?php

namespace App\Data\Repositories\DepartmentUser;

use App\Data\Entities\DepartmentUser;
use Illuminate\Support\Collection;

interface IDepartmentUserRepository
{
    public function getOneById(int $id): null|DepartmentUser;

    public function getAllByIds(array $ids): Collection;

    public function create(DepartmentUser $departmentUser): DepartmentUser;

    public function update(DepartmentUser $departmentUser): int;

}