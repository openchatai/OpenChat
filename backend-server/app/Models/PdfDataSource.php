<?php

namespace App\Models;

use App\Http\Enums\IngestStatusType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PdfDataSource extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'chatbot_id' => 'string',
        'files' => 'array',
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

    public function setFiles($files): void
    {
        $this->files = $files;
    }

    public function setFolderName($folderName): void
    {
        $this->folder_name = $folderName;
    }

    public function getFolderName(): string
    {
        return $this->folder_name;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setStatus(string $status): void
    {
        $this->ingest_status = $status;
    }

    public function getStatus(): IngestStatusType
    {
        return new IngestStatusType($this->ingest_status);
    }
}
