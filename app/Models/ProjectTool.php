<?php

namespace App\Models;

use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTool extends Model
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
        'name',
        'icon_id',
        'order',
    ];

    /**
     * Get the project that owns the tool.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the icon for this tool.
     */
    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }
}
