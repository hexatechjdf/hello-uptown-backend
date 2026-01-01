<?php

namespace App\Resources\Website\AllPages\News;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        $daysAgo = $this->published_at ? Carbon::parse($this->published_at)->diffInDays(Carbon::now()) : null;
        return [
            'id' => (string) $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->description, 150,
            'content' => $this->description,
            'image' => $this->image ?? null,
            'author' => $this->author ?? 'Admin',
            'daysAgo' => $daysAgo,
            'publishedAt' => $this->published_at ? Carbon::parse($this->published_at)->format('Y-m-d') : null,
            'externalUrl' => $this->article_url,
            'isFeatured' => (bool) $this->featured,
            'category' => $this->category ? $this->category->name : null,
        ];
    }
}
