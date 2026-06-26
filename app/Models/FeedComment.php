<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'feed_post_id',
        'user_id',
        'content',
    ];

    /**
     * Get the post this comment belongs to.
     */
    public function post()
    {
        return $this->belongsTo(FeedPost::class, 'feed_post_id');
    }

    /**
     * Get the user who wrote this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
