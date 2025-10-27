<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;

class ClientStoreRequestPanel extends BaseRequest
{

    public readonly string $name;
    public readonly int $departmentId;
    public readonly string $isActive;
    public readonly string|null $expiredAt;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64|min:2',
            'department_id' => 'required',
            'is_active' => 'nullable|boolean',
            'expired_at' => 'nullable|datetime'
        ];
    }

    protected function passedValidation(): void
    {
        $this->name = $this->get('name');
        $this->departmentId = $this->get('department_id');
        $this->isActive = $this->get('is_active', false);
        $this->expiredAt = $this->get('expired_at');
    }
}
