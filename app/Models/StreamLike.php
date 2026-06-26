<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamLike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stream_id',
        'user_id',
    ];

    /**
     * Get the stream this like belongs to.
     */
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * Get the user who liked this stream.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
