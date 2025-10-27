<?php


namespace App\Helpers;

use Pasoonate\Calendars\CalendarManager;
use Pasoonate\Pasoonate;

class JDate
{
    public function __construct(
        public CalendarManager $calendar
    )
    {
        $this->calendar = Pasoonate::make()->jalali();
    }
}
