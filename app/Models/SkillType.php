<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class SkillType extends Model
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
        'name',
        'slug',
        'order',
    ];

    /**
     * Get the skills for the skill type.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }
}
