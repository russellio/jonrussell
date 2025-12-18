<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'byline',
        'description',
        'primary_image_src',
        'primary_image_title',
        'primary_image_alt',
        'bg_image',
        'bg_position_x',
        'bg_position_y',
        'company_id',
        'order',
    ];

    /**
     * Get the company that owns the project.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the key takeaways for the project.
     */
    public function keyTakeaways(): HasMany
    {
        return $this->hasMany(ProjectKeyTakeaway::class)->orderBy('order');
    }

    /**
     * Get the images for the project.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    /**
     * Get the links for the project.
     */
    public function links(): HasMany
    {
        return $this->hasMany(ProjectLink::class)->orderBy('order');
    }

    /**
     * Get the technologies for the project.
     */
    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class)->orderBy('order');
    }

    /**
     * Get the highlighted technologies for the project.
     */
    public function highlightedTechnologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class)->where('is_highlighted', true)->orderBy('order');
    }

    /**
     * Get the tools for the project.
     */
    public function tools(): HasMany
    {
        return $this->hasMany(ProjectTool::class)->orderBy('order');
    }

    /**
     * Get the awards for the project.
     */
    public function awards(): HasMany
    {
        return $this->hasMany(ProjectAward::class)->orderBy('order');
    }
}
