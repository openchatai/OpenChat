<?php

namespace App\Http\Listeners;

use App\Http\Events\ChatbotWasCreated;
use App\Http\Events\WebsiteDataSourceWasAdded;
use App\Http\GetLogoFromUrlTrait;
use App\Models\WebsiteDataSource;
use Ramsey\Uuid\Uuid;

class CreateWebsiteDataSourceIfNeeded
{
    use GetLogoFromUrlTrait;

    public function handle($event)
    {
        if (!$event instanceof ChatbotWasCreated) {
            return;
        }

        if (!$event->getChatbotWebsite()) {
            return;
        }

        $botId = $event->getChatbotId();

        $dataSource = new WebsiteDataSource();
        $dataSource->setId(Uuid::uuid4());
        $dataSource->setChatbotId($botId);
        $dataSource->setRootUrl($event->getChatbotWebsite());
        $dataSource->setIcon($this->getLogo($event->getChatbotWebsite()));
        $dataSource->save();


        event(new WebsiteDataSourceWasAdded($botId, $dataSource->getId()));
    }

}
