<?php

namespace App\Http\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

class WebsiteDataSourceWasAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private UuidInterface $chatbotId,
        private UuidInterface $websiteDataSourceId,
    ) {
    }

    public function getChatbotId(): UuidInterface
    {
        return $this->chatbotId;
    }

    public function getWebsiteDataSourceId(): UuidInterface
    {
        return $this->websiteDataSourceId;
    }
}
