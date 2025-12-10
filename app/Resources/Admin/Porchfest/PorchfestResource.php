<?php
namespace App\Resources\Admin\Porchfest;

use Illuminate\Http\Resources\Json\JsonResource;

class PorchfestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'subheadingPrimary' => $this->subheading_primary,
            'subheadingSecondary' => $this->subheading_secondary,
            'description' => $this->description,
            'imageUrl' => $this->image ? asset('storage/'.$this->image) : null,
            'availableSeats' => $this->available_seats,
            'categories' => $this->categories,
            'features' => $this->features,
            'address' => $this->address,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'eventDate' => $this->event_date?->format('d/m/Y'),
            'day' => $this->day,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'status' => $this->status,
        ];
    }
}

?>