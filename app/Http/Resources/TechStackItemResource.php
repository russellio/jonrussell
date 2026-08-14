<?php

namespace App\Http\Resources;

use App\Models\TechStackItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TechStackItem
 */
class TechStackItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tech' => $this->name,
            'percent' => (string) $this->calculated_percent,
            'iconType' => $this->icon?->icon_type,
            'iconName' => $this->icon?->icon_name,
            'active' => $this->active,
        ];
    }
}
