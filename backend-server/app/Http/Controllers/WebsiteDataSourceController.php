<?php

namespace App\Http\Controllers;

use App\Http\Events\WebsiteDataSourceWasAdded;
use App\Http\GetLogoFromUrlTrait;
use App\Http\Requests\AddWebsiteDataSourceRequest;
use App\Models\Chatbot;
use App\Models\WebsiteDataSource;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class WebsiteDataSourceController extends Controller
{
    use GetLogoFromUrlTrait;

    public function show(Request $request, $id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();
        return view('onboarding.other-data-sources-website', ['bot' => $bot]);
    }

    public function create(AddWebsiteDataSourceRequest $request, $id){
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();

        $dataSource = new WebsiteDataSource();
        $dataSource->setId(Uuid::uuid4());
        $dataSource->setChatbotId($bot->getId());
        $dataSource->setRootUrl($request->getWebsite());
        $dataSource->setIcon($this->getLogo($request->getWebsite()));
        $dataSource->save();

        event(new WebsiteDataSourceWasAdded($bot->getId(), $dataSource->getId()));

        return redirect()->route('chatbot.settings-data', ['id' => $bot->getId()->toString()]);
    }
}
