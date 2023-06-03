<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

class CodebaseDataSourceWasAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private UuidInterface $chatbotId,
        private UuidInterface $codebaseDataSourceId,
    ) {
    }

    public function getChatbotId(): UuidInterface
    {
        return $this->chatbotId;
    }

    public function getCodebaseDataSourceId(): UuidInterface
    {
        return $this->codebaseDataSourceId;
    }
}
