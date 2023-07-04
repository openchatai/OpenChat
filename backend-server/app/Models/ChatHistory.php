<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

class ChatHistory extends Model
{
    use HasFactory;

    protected $fillable = ['chatbot_id', 'from', // user or bot
        'message', 'session_id'];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function isFromUser(): bool
    {
        return $this->from === 'user';
    }

    public function isFromBot(): bool
    {
        return $this->from === 'bot';
    }

    public function setFromUser(): void
    {
        $this->from = 'user';
    }

    public function setFromBot(): void
    {
        $this->from = 'bot';
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function setChatbotId(UuidInterface $chatbotId): void
    {
        $this->chatbot_id = $chatbotId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->session_id = $sessionId;
    }

}
