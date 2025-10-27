<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Pagination;

class LineListRequestPanel extends BaseRequest
{
    use Pagination;

    protected function passedValidation(): void
    {
        $this->page = $this->get('page', 1);
        $this->perPage = $this->get('per_page', 10);
    }
}
