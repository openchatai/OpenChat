<?php

namespace App\Http\Enums;

class IngestStatusType
{
    public const SUCCESS= 'success';
    public const FAILED = 'failed';


    public function __construct(private readonly string $status)
    {
        if (!self::isValid($status)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
    }

    public static function getTypes(): array
    {
        return [
            self::SUCCESS,
            self::FAILED,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::SUCCESS => 'Success',
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

    public function isSuccessful(): bool
    {
        return $this->getStatus() === self::SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->getStatus() === self::FAILED;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function __toString(): string
    {
        return $this->getStatus();
    }

    public function equals(IngestStatusType $type): bool
    {
        return $this->getStatus() === $type->getStatus();
    }
}
