<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'startDate' => $this->start_date->format('Y-m-d'),
            'endDate' => $this->end_date?->format('Y-m-d'),
            'months' => $this->months,
            'isCurrent' => $this->end_date === null,
            'company' => $this->company ? [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'logo' => [
                    'src' => $this->company->logo_src,
                    'alt' => $this->company->logo_alt,
                    'displayName' => $this->company->logo_display_name,
                ],
                'link' => $this->company->link,
                'description' => $this->company->description,
            ] : null,
            'skills' => $this->skills->map(fn ($skill) => [
                'id' => $skill->id,
                'name' => $skill->name,
            ])->toArray(),
        ];
    }
}
