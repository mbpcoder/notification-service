<?php

namespace App\Http\Controllers;

use App\Http\Responses\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use ValidatesRequests;
    public function __construct(
        protected Response $response = new Response()
    )
    {

    }
}
