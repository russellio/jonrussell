<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            // ->toArray() is load-bearing: without it this value stays an Eloquent
            // Collection, which gets PHP-serialized whole into the cache store.
            'skills' => $this->skills->map(fn ($skill) => [
                'id' => $skill->id,
                'name' => $skill->name,
                'iconType' => $skill->icon?->icon_type,
                'iconName' => $skill->icon?->icon_name,
            ])->toArray(),
        ];
    }
}
