<?php

namespace App\Models;

use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use App\Queries\TimelineQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        $bust = function (self $model) {
            (new TimelineQuery)->forget();
            (new ProjectsQuery)->forget();

            foreach ($model->projects()->pluck('slug') as $slug) {
                (new ProjectQuery($slug))->forget();
            }
        };

        // `deleting`, not `deleted`: projects.company_id is ON DELETE SET NULL,
        // so by the time `deleted` fires this company's projects are already
        // unreachable via the projects() relation.
        static::saved($bust);
        static::deleting($bust);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo_src',
        'logo_alt',
        'logo_display_name',
        'link',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logo_display_name' => 'boolean',
        ];
    }

    /**
     * Get the positions for the company.
     */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the projects for the company.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
