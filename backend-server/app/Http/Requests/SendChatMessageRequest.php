<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;

class SendChatMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'mode' => 'sometimes|string|in:assistant,pair_programmer',
        ];
    }

    public function getMessage(): string
    {
        return $this->get('message');
    }

    public function getHistory(): array
    {
        return $this->get('history');
    }

    public function getMode(): string
    {
        return $this->get('mode');
    }
}
