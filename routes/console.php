<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new \App\Jobs\GetReadyToSendSms());
})->everyMinute();
