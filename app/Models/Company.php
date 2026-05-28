<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Company extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        $bust = function () {
            Cache::forget('timeline.index');
            Cache::forget('projects:list');
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
        'name',
        'logo_src',
        'logo_alt',
        'logo_display_name',
        'link',
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
}
