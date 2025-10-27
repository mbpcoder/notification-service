<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Sms;
use App\Data\Repositories\Sms\SmsRepository;
use App\Data\Repositories\SmsAttempt\SmsAttemptRepository;
use App\Data\Resources\SmsResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SmsListRequestPanel;
use Illuminate\Http\JsonResponse;

class SmsController extends Controller
{
    public function __construct(
        private readonly SmsRepository $smsRepository,
        private readonly SmsAttemptRepository $smsAttemptRepository,
        private readonly SmsResource   $smsResource
    )
    {
        parent::__construct();

    }

    public function list(SmsListRequestPanel $request): JsonResponse
    {
        [$total, $allSms] = $this->smsRepository->getAll($request->offset(), $request->perPage);
        $smsIds = $allSms->pluck('id')->toArray();
        $smsAttempts = $this->smsAttemptRepository->getAllBySmsIds($smsIds)->groupBy('smsId');
        // attach attempts to each Sms
        $allSms->each(function (Sms $sms) use ($smsAttempts) {
            $sms->attempts = $smsAttempts->get($sms->id, collect());
        });

        $this->response->value->add('total', $total);
        $this->response->value->add('sms', $this->smsResource->collectionToArrayWithForeignKeys($allSms));
        return $this->response->toJson();
    }
}
