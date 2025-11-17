<?php

declare(strict_types=1);

namespace App\Data\DataTransferObject;

use DateTime;

readonly class SendBulkSmsDto
{
    public function __construct(
        public string|null   $provider,
        public string|null   $line,
        public array         $mobileList,
        public string        $message,
        public DateTime      $expiredAt,
        public DateTime|null $dueAt,
    )
    {
    }
}
