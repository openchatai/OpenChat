<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;

class CreateChatbotViaJsonFlowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'jsonfiles' => 'required',
        ];
    }

    public function getName(): string
    {
        return $this->get('name', 'My first chatbot');
    }

    public function getWebsite(): string
    {
        return $this->get('website');
    }

    public function getPromptMessage(): string
    {
        return $this->get('prompt_message', "");
    }

    public function getFiles()
    {
        return $this->get('jsonfiles');
    }
}
