<?php

namespace App\Data\Repositories\Sms;

use App\Data\Entities\Sms;
use Illuminate\Support\Collection;

interface ISmsRepository
{

    public function getOneById(int $id): null|Sms;

    public function getAllByIds(array $ids): Collection;

    public function getAllReadyToSendSms(int $limit = 50): Collection;

    public function getAllReadyToSendSmsByProviderId(int $providerId, int $limit = 50): Collection;

    public function create(Sms $sms): Sms;

    public function update(Sms $sms): int;

}
