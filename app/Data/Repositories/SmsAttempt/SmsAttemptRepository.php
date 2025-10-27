<?php

namespace App\Data\Repositories\SmsAttempt;

use App\Data\Entities\SmsAttempt;
use Illuminate\Support\Collection;

readonly class SmsAttemptRepository implements ISmsAttemptRepository
{

    public function __construct(
        private MySqlSmsAttemptRepository $repository
    )
    {

    }

    public function create(SmsAttempt $smsAttempt): SmsAttempt
    {
        return $this->repository->create($smsAttempt);
    }

    public function getAllBySmsId(int $smsId): Collection
    {
        return $this->repository->getAllBySmsId($smsId);
    }

    public function getAllBySmsIds(array $smsIds): Collection
    {
        return $this->repository->getAllBySmsIds($smsIds);
    }
}
