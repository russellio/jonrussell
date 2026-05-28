<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Skill extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        $bust = fn () => Cache::forget('skills.index');
        static::saved($bust);
        static::deleted($bust);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_type_id',
        'name',
        'order',
        'icon_id',
    ];

    /**
     * Get the skill type that owns the skill.
     */
    public function skillType(): BelongsTo
    {
        return $this->belongsTo(SkillType::class);
    }

    /**
     * Get the icon for this skill.
     */
    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }
}
