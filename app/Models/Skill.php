<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_type_id',
        'name',
        'order',
    ];

    /**
     * Get the skill type that owns the skill.
     */
    public function skillType(): BelongsTo
    {
        return $this->belongsTo(SkillType::class);
    }
}
