<?php

use App\Contracts\ApplicationClientInterface;

function myDate(): \Pasoonate\Calendars\CalendarManager
{
    return app(\App\Helpers\JDate::class)->calenndar;
}

function apiClient(): \App\Data\Entities\Client
{
    $apiClient = app(ApplicationClientInterface::class);
    return $apiClient->client();
}

function authClient()
{
    return app('client');
}

function leading_zero(string $str, int $length = 11): string
{
    return str_pad((string)$str, $length, '0', STR_PAD_LEFT);
}

function ago(int $time, int $step = 1): string
{
    $result = '';

    $now = time();
    // catch error
    if (!$time) {
        return false;
    }

    // get difference
    $difference = $now - $time;
    // set descriptor
    if ($difference < 0) {
        $difference = abs($difference); // absolute value
        $negative = true;
    }

    // build period and periods In Second array
    $periods = ['ثانیه', 'دقیقه', 'ساعت', 'روز', 'ماه', 'سال', 'قرن'];
    $periodsInSecond = [1, 60, 3600, 86400, 2_592_000, 31_104_000, 3_110_400_000];

    // do math
    $stepChecker = 1;
    $addConcatWordToResult = false;
    for ($i = count($periodsInSecond) - 1; $i > 0; $i--) {

        if ($difference < $periodsInSecond[$i]) continue;
        $quotient = $difference / $periodsInSecond[$i];
        if ($quotient < 1) continue;

        $quotient = floor($quotient);

        $difference = $difference - ($quotient * $periodsInSecond[$i]);

        if ($addConcatWordToResult) $result .= ' و ';

        $result .= $quotient . ' ' . $periods[$i];

        $addConcatWordToResult = true;
        if ($stepChecker++ == $step) break;
    }

    if ($result === "") {
        return "الان";
    }

    return $result . (isset($negative) ? '' : ' پیش');
}

function convertToPersianNumbers(string $string): string
{
    return str_replace(
        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'],
        ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫'],
        (string)$string);
}

function convertToEnglishNumbers(string $string): string
{
    return str_replace(
        [
            '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹',
            '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'
        ],
        [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ],
        $string);
}

function generateToken($length = 64): string
{
    $possibleChars = '123456789abcdefghikmnopqrstyxwz';
    $possibleCharsIndex = strlen($possibleChars) - 1;

    $rndString = '';
    for ($i = 0; $i < $length; $i++) {
        $rndString .= $possibleChars[rand(0, $possibleCharsIndex)];
    }
    return $rndString;
}
