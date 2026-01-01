<?php

namespace App\Resources\Admin\News;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'imageUrl' => $this->image ?? null,
            'slug' => $this->slug,
            'featured' => (bool) $this->featured,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug
            ] : null,
            'category_id' => $this->category_id,
            'articleUrl' => $this->article_url,
            'publishedAt' => $this->published_at,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d\TH:i:s.v\Z'),
            'updatedAt' => $this->updated_at->format('Y-m-d\TH:i:s.v\Z'),
        ];
    }
}
