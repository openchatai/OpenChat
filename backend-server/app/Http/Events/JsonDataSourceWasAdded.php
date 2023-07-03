<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

class JsonDataSourceWasAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private UuidInterface $chatbotId,
        private UuidInterface $jsonDataSourceId,
    ) {
    }

    public function getChatbotId(): UuidInterface
    {
        return $this->chatbotId;
    }

    public function getJsonDataSourceId(): UuidInterface
    {
        return $this->jsonDataSourceId;
    }
}
