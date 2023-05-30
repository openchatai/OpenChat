<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UpdateCharacterSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'character_name' => 'nullable|string|in:knowledgeable,wise',
        ];
    }


    public function getCharacterName(): string
    {
        return $this->get('character_name', 'knowledgeable');
    }

    public function getChatbotId(): UuidInterface
    {
        return Uuid::fromString($this->route('id'));
    }
}
