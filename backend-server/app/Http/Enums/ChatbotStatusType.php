<?php

namespace App\Http\Enums;

class ChatbotStatusType
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';
    public const ARCHIVED = 'archived';


    public function __construct(private readonly string $status)
    {
        if (!self::isValid($status)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
    }

    public static function getTypes(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        ];
    }

   public function getLabel(): string
    {
        return self::getLabels()[$this->getStatus()];
    }

    public static function getValues(): array
    {
        return array_keys(self::getLabels());
    }

    public static function isValid(string $type): bool
    {
        return in_array($type, self::getValues());
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isDraft(): bool
    {
        return $this->getStatus() === self::DRAFT;
    }

    public function isPublished(): bool
    {
        return $this->getStatus() === self::PUBLISHED;
    }

    public function isArchived(): bool
    {
        return $this->getStatus() === self::ARCHIVED;
    }


}
