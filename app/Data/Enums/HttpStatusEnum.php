<?php

namespace App\Data\Enums;

enum HttpStatusEnum: int
{
    case OK = 200;
    case CREATE = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;

    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case CONFLICT = 409;
    case RESOURCE_GONE = 410;
    case METHOD_FAILURE = 420;
    case UNPROCESSABLE_ENTITY = 422;
    case TOO_MANY = 429;
    case UNKNOWN = 500;


    /**
     * @param $statusCode
     * @return string
     */
    public static function getDescription($statusCode): string
    {

        return match ($statusCode) {
            self::OK => 'OK',
            self::CREATE => 'Object Created',
            self::ACCEPTED => 'Accepted For Processing',
            self::NO_CONTENT => 'No Content',

            self::BAD_REQUEST => 'Bad Request',
            self::UNAUTHORIZED => 'Not Authorized',
            self::PAYMENT_REQUIRED => 'Payment is Required',
            self::FORBIDDEN => 'Access Denied',
            self::NOT_FOUND => 'Not Found',
            self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
            self::NOT_ACCEPTABLE => 'Not Acceptable',
            self::CONFLICT => 'Conflict',
            self::RESOURCE_GONE => 'Deprecated Api',
            self::METHOD_FAILURE => 'Method Failure',
            self::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
            self::TOO_MANY => 'Too Many Request',

            default => 'UnKnown Error'
        };
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return self::getDescription($this);
    }
}
