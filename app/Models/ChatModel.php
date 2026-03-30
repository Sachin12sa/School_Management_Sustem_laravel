<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatModel extends Model
{
    use HasFactory;

    protected $table = 'chat';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'file',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Return the public URL to the attached file, or empty string if missing.
     */
    public function getFile(): string
    {
        if (!empty($this->file) && file_exists(storage_path('app/public/chat/' . $this->file))) {
            return url('storage/chat/' . $this->file);
        }
        return '';
    }
}