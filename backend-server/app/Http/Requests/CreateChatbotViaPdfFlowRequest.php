<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;

class CreateChatbotViaPdfFlowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pdffiles' => 'required',
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
        return $this->get('pdffiles');
    }
}
