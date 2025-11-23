<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    use HasFactory;

    /**
     * Valid icon types.
     */
    public const VALID_ICON_TYPES = ['fa', 'si'];

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
        if ($value !== null && !in_array($value, self::VALID_ICON_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid icon_type: {$value}. Must be one of: " . implode(', ', self::VALID_ICON_TYPES));
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
