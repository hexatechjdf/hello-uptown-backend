<?php
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerMarketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'availableVendors' => $this->available_vendors,
            'tags' => $this->tags,
            'subTags' => $this->sub_tags,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website,
            'date' => $this->date,
            'day' => $this->day,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'featured' => $this->featured,
            'status' => $this->status,
            'createdAt' => $this->created_at,
        ];
    }
}
?>