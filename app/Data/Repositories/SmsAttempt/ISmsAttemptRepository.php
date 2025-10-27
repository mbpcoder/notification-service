<?php

namespace App\Data\Repositories\SmsAttempt;

use App\Data\Entities\SmsAttempt;
use Illuminate\Support\Collection;

interface ISmsAttemptRepository
{

    public function getAllBySmsId(int $smsId): Collection;

    public function getAllBySmsIds(array $smsIds): Collection;

    public function create(SmsAttempt $smsAttempt): SmsAttempt;

}
