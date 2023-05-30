<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CrawledPages extends Model
{
    use HasFactory;

    protected $table = 'crawled_pages';

    protected $fillable = [
        'id',
        'chatbot_id',
        'website_data_source_id',
        'url',
        'title',
        'status_code',
        'content',
        'normalized_content',
    ];

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getChatbotId(): UuidInterface
    {
        return Uuid::fromString($this->chatbot_id);
    }

    public function getWebsiteDataSourceId(): UuidInterface
    {
        return Uuid::fromString($this->website_data_source_id);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function getStatusCode(): string
    {
        return $this->status_code;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getNormalizedContent(): string
    {
        return $this->normalized_content;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function setChatbotId(UuidInterface $chatbotId): void
    {
        $this->chatbot_id = $chatbotId;
    }

    public function setWebsiteDataSourceId(UuidInterface $websiteDataSourceId): void
    {
        $this->website_data_source_id = $websiteDataSourceId;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setStatusCode(?string $statusCode): void
    {
        $this->status_code = $statusCode;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function setNormalizedContent(?string $normalizedContent): void
    {
        $this->normalized_content = $normalizedContent;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }
}
