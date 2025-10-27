<?php

namespace App\Data\Repositories\Sms;

use App\Data\Entities\Sms;
use Illuminate\Support\Collection;

readonly class SmsRepository implements ISmsRepository
{

    public function __construct(
        private MySqlSmsRepository $repository
    )
    {

    }

    public function getCurrentMonthSmsCountByDepartmentId(int $departmentId): int
    {
        return $this->repository->getCurrentMonthSmsCountByDepartmentId($departmentId);
    }

    public function getOneById(int $id): null|Sms
    {
        return $this->repository->getOneById($id);
    }

    public function getAllByIds(array $ids): Collection
    {
        return $this->repository->getAllByIds($ids);
    }

    public function getAllReadyToSendSms(int $limit = 50): Collection
    {
        return $this->repository->getAllReadyToSendSms($limit);
    }

    public function getAll(int $offset, int $count): array
    {
        return $this->repository->getAll($offset, $count);
    }

    public function create(Sms $sms): Sms
    {
        return $this->repository->create($sms);
    }

    public function update(Sms $sms): int
    {
        return $this->repository->update($sms);
    }

    public function updateSendingSms(Collection|array $allSms): int
    {
        return $this->repository->updateSendingSms($allSms);
    }

}
