<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stream extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'video_url',
        'thumbnail',
        'duration',
        'views_count',
        'likes_count',
        'category',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the user who uploaded this stream.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes on this stream.
     */
    public function likes()
    {
        return $this->hasMany(StreamLike::class);
    }
}
