<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source,
            'author' => $this->author,
            'category' => $this->category,
            'url' => $this->url,
            'imageUrl' => $this->image_url,
            'publishedAt' => $this->published_at,
            'createdAt' => $this->created_at
        ];
    }
}
