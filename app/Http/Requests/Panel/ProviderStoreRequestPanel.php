<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;

class ProviderStoreRequestPanel extends BaseRequest
{

    public readonly string $name;
    public readonly string $className;
    public readonly string $slug;
    public readonly string|null $username;
    public readonly string|null $password;
    public readonly string|null $token;
    public readonly string|null $url;
    public readonly bool $isActive;
    public readonly bool $isDefault;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64|min:2',
            'class_name' => 'required|string|max:32|min:2',
            'username' => 'nullable|string|max:32|min:2',
            'password' => 'nullable|string|max:32|min:2',
        ];
    }

    protected function passedValidation(): void
    {
        $this->name = $this->get('name');
        $this->className = $this->get('class_name');
        $this->slug = $this->get('slug');
        $this->username = $this->get('username');
        $this->password = $this->get('password');
        $this->token = $this->get('token');
        $this->url = $this->get('url');
        $this->isActive = $this->get('is_active', false);
        $this->isDefault = $this->get('is_default', false);
    }
}
