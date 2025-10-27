<?php

namespace App\Services;

use App\Data\Entities\Sms;
use App\Data\Repositories\Limitation\LimitationRepository;
use App\Data\Repositories\Sms\SmsRepository;

readonly class LimitationService
{

    const SMS_PART_CHARACTER_COUNT = 65;

    public function __construct(
        private LimitationRepository $limitationRepository = new LimitationRepository(),
        private SmsRepository        $smsRepository = new SmsRepository()
    )
    {

    }

    public function isWithinLimit(Sms $sms): bool
    {
        $limitation = $this->limitationRepository->getOneActiveByDepartmentId($sms->departmentId);

        if ($limitation === null) {
            return true;
        }

        if ($limitation->allowedSmsPartCount !== null) {
            if (mb_strlen($sms->message) > ($limitation->allowedSmsPartCount * self::SMS_PART_CHARACTER_COUNT)) {
                return false;
            }
        }

        if ($limitation->monthlyAllowedSmsCount !== null) {
            if ($this->smsRepository->getCurrentMonthSmsCountByDepartmentId($sms->departmentId) >= $limitation->monthlyAllowedSmsCount) {
                return false;
            }
        }

        return true;
    }

}
