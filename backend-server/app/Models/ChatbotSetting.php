<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

class ChatbotSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'chatbot_id',
        'name',
        'value',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'chatbot_id' => 'string',
    ];

    public function setChatbotId(UuidInterface $chatbotId): void
    {
        $this->chatbot_id = $chatbotId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }


    public function getChatbotId(): UuidInterface
    {
        return $this->chatbot_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

}
