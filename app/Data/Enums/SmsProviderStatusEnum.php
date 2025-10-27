<?php

namespace App\Data\Enums;

enum SmsProviderStatusEnum: string
{
    case SUCCESS = 'success';
    case UNKNOWN = 'unknown';
    case MOBILE_NOT_VALID = 'mobile_not_valid';
    case MOBILE_ARRAY_IS_EMPTY = 'mobile_array_is_empty';
    case MOBILE_ARRAY_IS_BIGGER_THAN_EXPECTED = 'mobile_array_is_bigger_than_expected';
    case LINE_NOT_VALID = 'line_not_valid';
    case LINE_ARRAY_IS_EMPTY = 'line_is_empty';
    case LINE_ARRAY_IS_BIGGER_THAN_EXPECTED = 'line_array_is_bigger_than_expected';
    case ENCODING_NOT_VALID = 'encoding_not_valid';
    case MESSAGE_CLASS_NOT_VALID = 'message_class_not_valid';
    case SERVER_ERROR = 'server_error';
    case BALANCE_IS_LOW = 'balance_is_low';
    case ACCOUNT_IS_DISABLED = 'account_is_disabled';
    case ACCOUNT_IS_EXPIRED = 'account_is_expired';
    case ACCOUNT_CREDENTIAL_NOT_VALID = 'account_credential_not_valid';
    case SERVER_NOT_RESPONDING = 'server_not_responding';
    case REQUESTED_SERVICE_IS_NOT_VALID = 'requested_service_is_not_valid';
    case MOBILE_CANCELED_RECEIVING_SMS = 'mobile_canceled_receiving_sms';
    case REQUEST_THROTTLE_PASSED = 'request_throttle_passed';
    case CREDENTIAL_IS_NOT_VALID = 'credential_is_not_valid';

}
