<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

class ChatbotWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private UuidInterface $chatbotId,
        private string $chatbotName,
        private ?string $chatbotWebsite,
        private string $chatbotPromptMessage,
    ) {
    }

    public function getChatbotId(): UuidInterface
    {
        return $this->chatbotId;
    }

    public function getChatbotName(): string
    {
        return $this->chatbotName;
    }

    public function getChatbotWebsite(): ?string
    {
        return $this->chatbotWebsite;
    }

    public function getChatbotPromptMessage(): string
    {
        return $this->chatbotPromptMessage;
    }
}
