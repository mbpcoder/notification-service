<?php

namespace App\Data\Repositories\SmsAttempt;

use App\Data\Entities\SmsAttempt;
use App\Data\Factories\SmsAttemptFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlSmsAttemptRepository extends MySqlRepository implements ISmsAttemptRepository
{
    public function __construct()
    {
        $this->table = 'sms_attempts';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new SmsAttemptFactory();

        parent::__construct();
    }


    public function create(SmsAttempt $smsAttempt): SmsAttempt
    {
        $smsAttempt->createdAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'sms_id' => $smsAttempt->smsId,
                'provider_status' => $smsAttempt->providerStatus->value ?? null,
                'response' => $smsAttempt->response,
                'response_code' => $smsAttempt->responseCode,
                'created_at' => $smsAttempt->createdAt,
            ]);

        $smsAttempt->id = $id;

        return $smsAttempt;
    }

    public function getAllBySmsId(int $smsId): Collection
    {
        $attempts = $this->newQuery()->where('sms_id', $smsId)->get();
        return $this->factory->makeCollectionOfEntities($attempts);
    }

    public function getAllBySmsIds(array $smsIds): Collection
    {
        $attempts = $this->newQuery()->whereIn('sms_id', $smsIds)->get();
        return $this->factory->makeCollectionOfEntities($attempts);
    }
}
