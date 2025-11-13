<?php

namespace App\Data\Repositories\Sms;

use App\Data\Entities\Sms;
use App\Data\Enums\OrderDirectionEnum;
use App\Data\Enums\SmsStatusEnum;
use App\Data\Factories\SmsFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MySqlSmsRepository extends MySqlRepository implements ISmsRepository
{
    public function __construct()
    {
        $this->table = 'sms';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new SmsFactory();

        parent::__construct();
    }

    public function getCurrentMonthSmsCountByDepartmentId(int $departmentId): int
    {
        $startOfMonth = myDate()->startOfMonth()->format('Y-m-d');
        $endOfMonth = myDate()->endOfMonth()->format('Y-m-d');

        return $this->newQuery()
            ->where('department_id', $departmentId)
            ->count();

//            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
//            ->count();
    }

    public function getOneById(int $id): null|Sms
    {
        $sms = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $sms ? $this->factory->makeEntityFromStdClass($sms) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $sms = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($sms);
    }

    public function getAllReadyToSendSms(int $limit = 50): Collection
    {
        $now = $this->now();

        $allSms = $this->newQuery()
            ->where('status', SmsStatusEnum::PENDING->value)
            ->where(function ($query) use ($now) {
                $query->whereNull('due_at')
                    ->orWhere('due_at', '<', $now);
            })
            ->where('expired_at', '>', $now)
            ->orderBy('retry_count', OrderDirectionEnum::ASC->value)
            ->limit($limit)
            ->get();

        return $this->factory->makeCollectionOfEntities($allSms);
    }

    public function getAllReadyToSendSmsByProviderId(int $providerId, int $limit = 50): Collection
    {
        $now = $this->now();

        $allSms = $this->newQuery()
            ->where('provider_id', $providerId)
            ->where('status', SmsStatusEnum::PENDING->value)
            ->where(function ($query) use ($now) {
                $query->whereNull('due_at')
                    ->orWhere('due_at', '<', $now);
            })
            ->where('expired_at', '>', $now)
            ->orderBy('retry_count', OrderDirectionEnum::ASC->value)
            ->limit($limit)
            ->get();

        return $this->factory->makeCollectionOfEntities($allSms);
    }

    public function create(Sms $sms): Sms
    {
        $now = $this->now();
        $sms->createdAt = $now;
        $sms->updatedAt = $now;

        $id = $this->newQuery()
            ->insertGetId([
                'department_id' => $sms->departmentId,
                'client_id' => $sms->clientId,
                'provider_id' => $sms->providerId,
                'line_id' => $sms->lineId,
                'mobile' => $sms->mobile,
                'template_name' => $sms->templateName,
                'template_parameter1' => $sms->templateParameter1,
                'template_parameter2' => $sms->templateParameter2,
                'template_parameter3' => $sms->templateParameter3,
                'template_parameter4' => $sms->templateParameter4,
                'message' => $sms->message,
                'retry_count' => $sms->retryCount,
                'status' => $sms->status->value,
                'due_at' => $sms->dueAt,
                'sent_at' => $sms->sentAt,
                'delivered_at' => $sms->deliveredAt,
                'expired_at' => $sms->expiredAt,
                'created_at' => $sms->createdAt,
                'updated_at' => $sms->updatedAt,
            ]);

        $sms->id = $id;

        return $sms;
    }

    public function update(Sms $sms): int
    {
        $sms->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $sms->getPrimaryKey())
            ->update([
                'department_id' => $sms->departmentId,
                'client_id' => $sms->clientId,
                'provider_id' => $sms->providerId,
                'line_id' => $sms->lineId,
                'mobile' => $sms->mobile,
                'template_name' => $sms->templateName,
                'template_parameter1' => $sms->templateParameter1,
                'template_parameter2' => $sms->templateParameter2,
                'template_parameter3' => $sms->templateParameter3,
                'template_parameter4' => $sms->templateParameter4,
                'message' => $sms->message,
                'retry_count' => $sms->retryCount,
                'status' => $sms->status->value,
                'due_at' => $sms->dueAt,
                'sent_at' => $sms->sentAt,
                'delivered_at' => $sms->deliveredAt,
                'expired_at' => $sms->expiredAt,
                'updated_at' => $sms->updatedAt,
            ]);
    }

    /**
     * @param Collection|array $allSms
     * @return int
     */
    public function updateSendingSms(Collection|array $allSms): int
    {
        $ids = $allSms->pluck('id')->toArray();

        $now = $this->now();

        return $this->newQuery()
            ->whereIn('id', $ids)
            ->update([
                'status' => SmsStatusEnum::SENDING,
                'retry_count' => DB::raw('retry_count + 1'),
                'updated_at' => $now,
            ]);
    }
}
