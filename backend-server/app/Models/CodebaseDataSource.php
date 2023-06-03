<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CodebaseDataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'repository',
        'chatbot_id',
        'ingested_at',
        'ingestion_status',
    ];

    protected $casts = [
        'ingested_at' => 'datetime',
    ];

    public $incrementing = false;


    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id->toString();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function getChatbotId(): UuidInterface
    {
        return $this->chatbot_id;
    }

    public function setChatbotId(UuidInterface $chatbotId): void
    {
        $this->chatbot_id = $chatbotId;
    }

    public function getIngestedAt(): DateTimeInterface
    {
        return $this->ingested_at;
    }

    public function setIngestedAt(DateTimeInterface $ingestedAt): void
    {
        $this->ingested_at = $ingestedAt;
    }

    public function getIngestionStatus(): string
    {
        return $this->ingestion_status;
    }

    public function setIngestionStatus(string $ingestionStatus): void
    {
        $this->ingestion_status = $ingestionStatus;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }
}
