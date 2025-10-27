<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class SendSmsRequest extends BaseRequest
{
    public readonly string|null $provider;
    public readonly string|null $line;
    public readonly string $mobile;
    public readonly string $message;
    public readonly string|null $dueAt;
    public readonly bool $isActive;
    public readonly string $expiredAt;

    public function rules(): array
    {
        return [
            'provider' => 'nullable|exists:providers,slug',
            'line' => 'nullable|numeric',
            'mobile' => 'required|numeric',
            'message' => 'required|string|min:10|max:255',
            'expired_at' => 'required|date',
            'due_at' => 'nullable|date',
        ];
    }

    protected function passedValidation(): void
    {
        $this->provider = $this->input('provider');
        $this->line = $this->input('line');
        $this->mobile = $this->input('mobile');
        $this->message = $this->input('message');
        $this->dueAt = $this->input('due_at');
        $this->isActive = $this->get('is_active', false);
        $this->expiredAt = $this->input('expired_at');
    }
}
