<?php

namespace App\Models;

use App\Queries\PostQuery;
use App\Queries\PostsQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::saved(function (Post $model) {
            (new PostsQuery)->forget();
            (new PostQuery($model->slug))->forget();
            if ($model->wasChanged('slug')) {
                (new PostQuery($model->getOriginal('slug')))->forget();
            }
        });

        static::deleted(function (Post $model) {
            (new PostsQuery)->forget();
            (new PostQuery($model->slug))->forget();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'image_src',
        'image_alt',
        'external_url',
        'published_at',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'order' => 'integer',
        ];
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
