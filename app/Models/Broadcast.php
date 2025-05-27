<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'sender_id',
        'status',
        'priority',
        'sent_at',
        'send_to_all',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'send_to_all' => 'boolean',
    ];

    protected $appends = ['file_url', 'is_read', 'read_at'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'broadcast_recipients', 'broadcast_id', 'user_id')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        // Jika menggunakan public disk
        return url(Storage::url($this->file_path));

        // Jika menggunakan private disk dengan signed URL
        // return Storage::temporaryUrl($this->file_path, now()->addMinutes(30));
    }

    public function getIsReadAttribute()
    {
        // Ini akan diisi oleh controller
        return $this->attributes['is_read'] ?? false;
    }

    public function getReadAtAttribute()
    {
        // Ini akan diisi oleh controller
        return $this->attributes['read_at'] ?? null;
    }
}
