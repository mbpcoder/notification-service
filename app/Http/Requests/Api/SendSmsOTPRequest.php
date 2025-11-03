<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class SendSmsOTPRequest extends BaseRequest
{
    public readonly string|null $provider;
    public readonly string|null $line;
    public readonly string $mobile;
    public readonly string|null $message;

    public function rules(): array
    {
        return [
            'provider' => 'nullable|exists:providers,slug',
            'line' => 'nullable|numeric',
            'mobile' => 'required|numeric',
            'message' => 'nullable|string|min:10|max:255',
        ];
    }

    protected function passedValidation(): void
    {
        $this->provider = $this->input('provider');
        $this->line = $this->input('line');
        $this->mobile = $this->input('mobile');
        $this->message = $this->input('message');
    }
}
