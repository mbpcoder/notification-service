<?php

namespace App\Data\Repositories\DepartmentUser;

use App\Data\Entities\DepartmentUser;
use App\Data\Factories\DepartmentUserFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlDepartmentUserRepository extends MySqlRepository implements IDepartmentUserRepository
{
    public function __construct()
    {
        $this->table = 'department_user';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new DepartmentUserFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|DepartmentUser
    {
        $departmentUser = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $departmentUser ? $this->factory->makeEntityFromStdClass($departmentUser) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $departmentUser = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($departmentUser);
    }

    public function create(DepartmentUser $departmentUser): DepartmentUser
    {
        $departmentUser->createdAt = $departmentUser->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'department_id' => $departmentUser->departmentId,
                'user_id' => $departmentUser->userId,
                'created_at' => $departmentUser->createdAt,
                'updated_at' => $departmentUser->updatedAt,
            ]);

        $departmentUser->id = $id;

        return $departmentUser;
    }

    public function update(DepartmentUser $departmentUser): int
    {
        $departmentUser->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $departmentUser->id)
            ->update([
                'department_id' => $departmentUser->departmentId,
                'user_id' => $departmentUser->userId,
                'updated_at' => $departmentUser->updatedAt,
            ]);
    }
}
