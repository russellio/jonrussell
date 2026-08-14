<?php

namespace App\Models;

use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectKeyTakeaway extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        $bust = function (self $model) {
            (new ProjectsQuery)->forget();
            if ($slug = $model->project?->slug) {
                (new ProjectQuery($slug))->forget();
            }
        };
        static::saved($bust);
        static::deleted($bust);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'text',
        'order',
    ];

    /**
     * Get the project that owns the key takeaway.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
