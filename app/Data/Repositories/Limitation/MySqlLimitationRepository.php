<?php

namespace App\Data\Repositories\Limitation;

use App\Data\Entities\Limitation;
use App\Data\Factories\LimitationFactory;
use App\Data\Repositories\MySqlRepository;

class MySqlLimitationRepository extends MySqlRepository implements ILimitationRepository
{

    public function __construct()
    {
        $this->table = 'limitations';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new LimitationFactory();

        parent::__construct();
    }

    public function getOneActiveByDepartmentId(int $departmentId): Limitation|null
    {
        $nowString = $this->now();
        $limitation = $this->newQuery()
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->where(function ($query) use ($nowString) {
                $query->where('start_at', '<=', $nowString)
                    ->orWhereNull('start_at');
            })
            ->where(function ($query) use ($nowString) {
                $query->where('end_at', '>', $nowString)
                    ->orWhereNull('end_at');
            })
            ->first();

        return $limitation ? $this->factory->makeEntityFromStdClass($limitation) : null;
    }

    public function getOneById(int $id): null|Limitation
    {
        $limitation = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $limitation ? $this->factory->makeEntityFromStdClass($limitation) : null;
    }

    public function create(Limitation $limitation): Limitation
    {
        $limitation->createdAt = $limitation->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'department_id' => $limitation->departmentId,
                'user_id' => $limitation->userId,
                'monthly_allowed_sms_count' => $limitation->monthlyAllowedSmsCount,
                'allowed_sms_part_count' => $limitation->allowedSmsPartCount,
                'start_at' => $limitation->startAt,
                'end_at' => $limitation->endAt,
                'is_active' => $limitation->isActive,
                'created_at' => $limitation->createdAt,
                'updated_at' => $limitation->updatedAt,
            ]);

        $limitation->id = $id;

        return $limitation;
    }

    public function update(Limitation $limitation): int
    {
        $limitation->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $limitation->id)
            ->update([
                'department_id' => $limitation->departmentId,
                'user_id' => $limitation->userId,
                'monthly_allowed_sms_count' => $limitation->monthlyAllowedSmsCount,
                'allowed_sms_part_count' => $limitation->allowedSmsPartCount,
                'start_at' => $limitation->startAt,
                'end_at' => $limitation->endAt,
                'is_active' => $limitation->isActive,
                'updated_at' => $limitation->updatedAt,
            ]);
    }
}
