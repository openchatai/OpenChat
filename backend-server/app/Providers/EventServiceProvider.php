<?php

namespace App\Providers;

use App\Http\Events\ChatbotWasCreated;
use App\Http\Events\PdfDataSourceWasAdded;
use App\Http\Events\WebsiteDataSourceCrawlingWasCompleted;
use App\Http\Events\WebsiteDataSourceWasAdded;
use App\Http\Events\CodebaseDataSourceWasAdded;
use App\Http\Listeners\CreateWebsiteDataSourceIfNeeded;
use App\Http\Listeners\IngestPdfDataSource;
use App\Http\Listeners\IngestWebsiteDataSource;
use App\Http\Listeners\StartRecursiveCrawler;
use App\Http\Listeners\IngestCodebaseDataSource;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ChatbotWasCreated::class => [
            CreateWebsiteDataSourceIfNeeded::class
        ],
        WebsiteDataSourceWasAdded::class => [
            StartRecursiveCrawler::class
        ],
        PdfDataSourceWasAdded::class => [
            IngestPdfDataSource::class
        ],
        WebsiteDataSourceCrawlingWasCompleted::class => [
            IngestWebsiteDataSource::class
        ],
        CodebaseDataSourceWasAdded::class => [
            IngestCodebaseDataSource::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
