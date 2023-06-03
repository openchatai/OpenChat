<?php

namespace App\Http\Responses;

class ChatbotResponse
{
    public function __construct(private array $response)
    {
    }

    public function getBotReply(): string
    {
        return $this->response['text'];
    }

    public function getSourceDocuments(): array
    {
        return $this->response['sourceDocuments'] ?? [];
    }
}
