<?php

namespace App\Http\Requests\Panel;

use App\Http\Requests\BaseRequest;

class LoginRequestPanel extends BaseRequest
{

    public readonly string $username;
    public readonly string $password;


    public function rules(): array
    {
        return [
            'username' => 'required|string|max:64|min:2|email',
            'password' => 'required|string|min:6',
        ];
    }

    protected function passedValidation(): void
    {
        $this->username = $this->get('username');
        $this->password = $this->get('password');
    }
}
