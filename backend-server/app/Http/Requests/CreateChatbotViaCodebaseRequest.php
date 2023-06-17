<?php

namespace App\Http\Requests;


use App\Http\Enums\ChatBotInitialPromptEnum;
use App\Http\Rules\GithubRepoUrlRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateChatbotViaCodebaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'repo' => ['required', new GithubRepoUrlRule],
        ];
    }

    public function getRepoUrl(): string
    {
        return $this->get('repo');
    }

    public function getRepoAccessToken(): ?string
    {
        return $this->get('token');
    }

    public function getName(): string
    {
        return $this->get('name', 'My first chatbot');
    }

    public function getPromptMessage(): string
    {
        return $this->get('prompt_message', ChatBotInitialPromptEnum::AI_ASSISTANT_INITIAL_PROMPT);
    }
}
