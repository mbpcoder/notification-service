<?php

namespace App\Http\Requests;

trait Pagination
{
    public int $page = 1;
    public int $perPage = 10;

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
