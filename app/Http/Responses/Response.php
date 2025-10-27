<?php

namespace App\Http\Responses;

use App\Data\Enums\HttpStatusEnum;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class Response implements Jsonable
{
    public HttpStatusEnum $code;

    public string|null $message;

    public string|null $fingerPrint;

    public Error $error;

    public ResponseValue $value;

    public function __construct(HttpStatusEnum $code = HttpStatusEnum::OK)
    {
        $this->code = $code;
        $this->fingerPrint = Str::uuid()->toString();
        $this->message = '';
        $this->error = new Error();
        $this->value = new ResponseValue();
    }

    /**
     * Convert to array
     *
     * @return array
     */
    #[ArrayShape([
            'code' => 'int',
            'fingerprint' => 'string',
            'message' => 'string',
            'errors' => 'array|null',
            'value' => 'array|null']
    )]
    public function toArray(): array
    {
        return [
            'code' => $this->code->value,
            'fingerprint' => $this->fingerPrint,
            'message' => $this->message,
            'errors' => $this->error->count() ? $this->error->toArray() : null,
            'value' => $this->value->count() ? $this->value->toArray() : null
        ];
    }


    /**
     * Convert to Json format
     *
     * @param int $options
     * @return JsonResponse
     */
    public function toJson($options = 0): JsonResponse
    {
        return response()->json($this->toArray(), $this->code->value);
    }
}
