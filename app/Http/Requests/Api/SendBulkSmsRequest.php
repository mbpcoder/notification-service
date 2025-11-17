<?php


namespace App\Http\Requests\Api;

use App\Data\DataTransferObject\SendBulkSmsDto;
use Illuminate\Foundation\Http\FormRequest;

class SendBulkSmsRequest extends FormRequest
{
    public SendBulkSmsDto $sendBulkSmsDto;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'provider' => ['nullable', 'exists:providers,slug'],
            'line' => ['nullable', 'numeric'],
            'mobile_list.*' => ['required', 'numeric'],
            'mobile_list' => ['required', 'array', "min:1"],
            'message' => ['required', 'string', 'min:10', 'max:255'],
            'expired_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function passedValidation(): void
    {
        $mobileList = array_unique($this->validated('mobile_list'));

        $this->sendBulkSmsDto = new SendBulkSmsDto(
            provider: $this->validated('provider'),
            line: $this->validated('line'),
            mobileList: $mobileList,
            message: $this->validated('message'),
            expiredAt: toDateTime($this->validated('expired_at')),
            dueAt: toDateTime($this->validated('due_at')),
        );
    }
}
