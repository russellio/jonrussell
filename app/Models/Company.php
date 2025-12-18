<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

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
