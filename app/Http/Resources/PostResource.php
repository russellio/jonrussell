<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\SanitizesHtml;
use App\Support\ReadingTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    use SanitizesHtml;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->sanitize($this->body),
            'year' => $this->published_at?->format('Y'),
            'publishedAt' => $this->published_at?->format('M j, Y'),
            'image' => $this->image_src ? [
                'src' => $this->image_src,
                'alt' => $this->image_alt ?? $this->title,
            ] : null,
            'externalUrl' => $this->external_url,
            'hasBody' => filled($this->body),
            'averageWordLength' => filled($this->body) ? ReadingTime::averageWordLength($this->body) : null,
        ];
    }
}
