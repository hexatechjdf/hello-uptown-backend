<?php

namespace App\Resources\Website\AllPages\News;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'slug' => Str::slug($this->heading),
            'title' => $this->heading,
            'excerpt' => $this->subheading,
            'content' => $this->description,
            'image' => $this->image ?? null,
            'author' => $this->author ?? 'Admin',
            'daysAgo' => $this->date ? Carbon::parse($this->date)->diffInDays(Carbon::now()): null,
            'publishedAt' => $this->date?->format('Y-m-d'),
            'externalUrl' => $this->website,
        ];
    }
}
