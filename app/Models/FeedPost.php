<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedPost extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'image',
        'is_pinned',
        'is_published',
        'likes_count',
        'comments_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the user who created this post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments on this post.
     */
    public function comments()
    {
        return $this->hasMany(FeedComment::class);
    }

    /**
     * Get the likes on this post.
     */
    public function likes()
    {
        return $this->hasMany(FeedLike::class);
    }
}
