<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechStackItem extends Model
{
    use HasFactory;

    /**
     * Maximum expected months for proficiency calculation (15 years = 180 months).
     */
    const MAX_EXPECTED_MONTHS = 180;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_id',
        'name',
        'percent',
        'icon_id',
        'active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'percent' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the skill that owns the tech stack item.
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Get the icon for this tech stack item.
     */
    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }

    /**
     * Get the icon type (backward compatibility accessor).
     */
    public function getIconTypeAttribute(): ?string
    {
        return $this->icon?->icon_type;
    }

    /**
     * Get the icon name (backward compatibility accessor).
     */
    public function getIconNameAttribute(): ?string
    {
        return $this->icon?->icon_name;
    }

    /**
     * Get the calculated proficiency percentage.
     * Returns manual percent if set, otherwise calculates from position experience.
     */
    public function getCalculatedPercentAttribute(): int
    {
        if ($this->percent !== null) {
            return $this->percent;
        }

        // Calculate from position_skill pivot table
        $totalMonths = 0;

        if ($this->skill_id) {
            $skill = $this->skill;
            if ($skill) {
                // Get all positions that used this skill
                $positions = \App\Models\Position::whereHas('skills', function ($query) use ($skill) {
                    $query->where('skills.id', $skill->id);
                })->get();

                foreach ($positions as $position) {
                    $totalMonths += $position->months;
                }
            }
        }

        // Calculate percentage: (total_months / max_expected_months) * 100
        $calculatedPercent = (int) round(($totalMonths / self::MAX_EXPECTED_MONTHS) * 100);

        // Cap at 100%
        return min($calculatedPercent, 100);
    }
}
