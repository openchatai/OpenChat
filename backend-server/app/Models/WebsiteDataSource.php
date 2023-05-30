<?php

namespace App\Models;

use App\Http\Enums\WebsiteDataSourceStatusType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class WebsiteDataSource extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'chatbot_id' => 'string',
        'html_files' => 'array',
    ];

    public function setChatbotId(UuidInterface $chatbotId): void
    {
        $this->chatbot_id = $chatbotId;
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getChatbotId(): UuidInterface
    {
        return Uuid::fromString($this->chatbot_id);
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function setRootUrl(string $rootUrl): void
    {
        $this->root_url = $rootUrl;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function setVectorDatabasedLastIngestedAt(string $vectorDatabasedLastIngestedAt): void
    {
        $this->vector_databased_last_ingested_at = $vectorDatabasedLastIngestedAt;
    }

    public function setCrawlingStatus(string $crawlingStatus): void
    {
        $this->crawling_status = $crawlingStatus;
    }


    public function getRootUrl(): string
    {
        return $this->root_url;
    }

    public function getIcon(): ?string
    {
        if (is_null($this->icon)) {
            return '/dashboard/images/user-40-07.jpg';
        }
        return url(Storage::url($this->icon));
    }

    public function getVectorDatabasedLastIngestedAt(): string
    {
        return $this->vector_databased_last_ingested_at;
    }

    public function getCrawlingStatus(): WebsiteDataSourceStatusType
    {
        return new WebsiteDataSourceStatusType($this->crawling_status);
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function setCrawlingProgress(float $crawlingProgress): void
    {
        $this->crawling_progress = $crawlingProgress;
    }

    public function getCrawlingProgress(): float
    {
        return $this->crawling_progress;
    }

    public function getCrawledPages()
    {
        return $this->hasMany(CrawledPages::class, 'website_data_source_id', 'id');
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }
}
