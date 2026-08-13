<?php

namespace App\Models;

use App\Queries\ProjectQuery;
use App\Queries\ProjectsQuery;
use App\Queries\SkillsQuery;
use App\Queries\TechStackQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    use HasFactory;

    /**
     * Valid icon types.
     */
    public const VALID_ICON_TYPES = ['lucide', 'simple-icons'];

    protected static function boot(): void
    {
        parent::boot();

        $bust = function (self $model) {
            (new SkillsQuery)->forget();
            (new TechStackQuery)->forget();
            (new ProjectsQuery)->forget();

            $slugs = Project::query()
                ->where(fn ($query) => $query
                    ->whereHas('technologies', fn ($q) => $q->where('icon_id', $model->id))
                    ->orWhereHas('tools', fn ($q) => $q->where('icon_id', $model->id)))
                ->pluck('slug');

            foreach ($slugs as $slug) {
                (new ProjectQuery($slug))->forget();
            }
        };

        // `deleting`, not `deleted`: technologies/tools.icon_id is ON DELETE SET
        // NULL, so by the time `deleted` fires the FK is already cleared and the
        // affected projects can no longer be found.
        static::saved($bust);
        static::deleting($bust);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'icon_type',
        'icon_name',
    ];

    /**
     * Set the icon type attribute, ensuring it's valid.
     */
    public function setIconTypeAttribute(?string $value): void
    {
        if ($value !== null && ! in_array($value, self::VALID_ICON_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid icon_type: {$value}. Must be one of: ".implode(', ', self::VALID_ICON_TYPES));
        }
        $this->attributes['icon_type'] = $value;
    }

    /**
     * Get the tech stack items that use this icon.
     */
    public function techStackItems(): HasMany
    {
        return $this->hasMany(TechStackItem::class);
    }

    /**
     * Get the skills that use this icon.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
