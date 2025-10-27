<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;

class LineStoreRequestPanel extends BaseRequest
{
    public readonly int $providerId;
    public readonly int $number;
    public readonly string $description;
    public readonly bool $isActive;
    public readonly bool $isDefault;
    public readonly bool $isReceivable;

    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:providers,id',
            'number' => 'required|string|max:32|min:2',
            'description' => 'nullable|string|max:255|min:2',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_receivable' => 'nullable|boolean',
        ];
    }

    protected function passedValidation(): void
    {
        $this->providerId = $this->get('provider_id');
        $this->number = $this->get('number');
        $this->description = $this->get('description');
        $this->isActive = $this->get('is_active', false);
        $this->isDefault = $this->get('is_default', false);
        $this->isReceivable = $this->get('is_receivable', false);
    }
}
