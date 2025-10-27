<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;

class DepartmentStoreRequestPanel extends BaseRequest
{

    public readonly string $name;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64|min:2'
        ];
    }

    protected function passedValidation(): void
    {
        $this->name = $this->get('name');
    }
}
