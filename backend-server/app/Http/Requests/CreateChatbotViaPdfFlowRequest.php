<?php

namespace App\Http\Requests;



use App\Http\Enums\ChatBotInitialPromptEnum;
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
        return $this->get('prompt_message', ChatBotInitialPromptEnum::AI_ASSISTANT_INITIAL_PROMPT);
    }

    public function getFiles()
    {
        return $this->get('pdffiles');
    }
}
