<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'sent_at',
        'send_to_all',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'send_to_all' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'broadcast_recipients')
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
