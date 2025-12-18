<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'url',
        'order',
    ];

    /**
     * Get the project that owns the link.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
