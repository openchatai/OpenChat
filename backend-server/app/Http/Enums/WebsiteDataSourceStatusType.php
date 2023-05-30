<?php

namespace App\Http\Enums;

class WebsiteDataSourceStatusType
{
    public const PENDING = 'pending';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';

    public const FAILED = 'failed';


    public function __construct(private readonly string $status)
    {
        if (!self::isValid($status)) {
            throw new \InvalidArgumentException("Invalid website crawling status: {$status}");
        }
    }

    public static function getTypes(): array
    {
        return [
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
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

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::FAILED;
    }
}
