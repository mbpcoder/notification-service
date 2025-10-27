<?php

namespace App\Data\Enums;

enum SmsStatusEnum: string
{
    case PENDING = 'pending';
    case SENDING = 'sending';
    case SUCCESS = 'success';
    case ERROR = 'error';
}
