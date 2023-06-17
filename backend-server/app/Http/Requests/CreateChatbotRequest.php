<?php

namespace App\Http\Requests;



use App\Http\Enums\ChatBotInitialPromptEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateChatbotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'website' => 'required|string|url',
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
}
