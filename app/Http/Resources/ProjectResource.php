<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\SanitizesHtml;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    use SanitizesHtml;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'byline' => $this->byline,
            'keyTakeaways' => $this->keyTakeaways->pluck('text')->toArray(),
            'description' => $this->sanitize($this->description),
            'highlightedSkills' => $this->technologies->where('is_highlighted', true)->pluck('name')->values()->toArray(),
            'technologies' => $this->technologies->map(fn ($tech) => [
                'name' => $tech->name,
                'iconType' => $tech->icon?->icon_type,
                'iconName' => $tech->icon?->icon_name,
            ])->toArray(),
            'tools' => $this->tools->map(fn ($tool) => [
                'name' => $tool->name,
                'iconType' => $tool->icon?->icon_type,
                'iconName' => $tool->icon?->icon_name,
            ])->toArray(),
            'company' => $this->company ? [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'logo' => [
                    'src' => $this->company->logo_src,
                    'alt' => $this->company->logo_alt,
                    'displayName' => $this->company->logo_display_name,
                ],
                'link' => $this->company->link,
            ] : null,
            'primaryImage' => $this->primary_image_src ? [
                'src' => $this->primary_image_src,
                'title' => $this->primary_image_title,
                'alt' => $this->primary_image_alt ?? $this->primary_image_title,
            ] : null,
            'bgImage' => $this->bg_image,
            'images' => $this->images->map(fn ($image) => [
                'src' => $image->src,
                'title' => $image->title,
                'alt' => $image->alt ?? $image->title,
            ])->toArray(),
            'bgPositionX' => $this->bg_position_x,
            'bgPositionY' => $this->bg_position_y,
            'links' => $this->links->map(fn ($link) => [
                'title' => $link->title,
                'url' => $link->url,
            ])->toArray(),
            'awards' => $this->awards->pluck('text')->toArray(),
        ];
    }
}
