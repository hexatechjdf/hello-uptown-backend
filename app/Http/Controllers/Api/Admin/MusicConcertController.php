<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MusicConcert;
use App\Services\Admin\MusicConcertService;
use App\Http\Resources\Admin\MusicConcertResource;
use App\Helpers\ApiResponse;

class MusicConcertController extends Controller
{
    public function index(Request $request)
    {
        $query = MusicConcert::query();

        // Search
        if ($request->search) {
            $query->where('main_heading', 'like', "%{$request->search}%");
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Sort by date
        $query->orderBy(
            $request->get('sortBy', 'event_date'),
            $request->get('order', 'desc')
        );

        $concerts = $query->paginate($request->get('perPage', 10));

        return ApiResponse::resource(
            MusicConcertResource::collection($concerts),
            'Music & concerts list'
        );
    }

    public function store(Request $request, MusicConcertService $service)
    {
        $data = $request->validate([
            'main_heading' => 'required|string|max:255',
            'sub_heading' => 'nullable|string',
            'event_description' => 'required|string',
            'image' => 'nullable|image',
            'available_attendees' => 'nullable|integer',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'website' => 'nullable|url',
            'event_date' => 'nullable|date',
            'status' => 'required|in:draft,active,scheduled,expired',
        ]);

        $concert = $service->store($data);

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert created'
        );
    }

    public function update(Request $request, MusicConcert $musicConcert, MusicConcertService $service)
    {
        $data = $request->validate([
            'main_heading' => 'sometimes|string|max:255',
            'sub_heading' => 'sometimes|string',
            'event_description' => 'sometimes|string',
            'image' => 'sometimes|image',
            'available_attendees' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'website' => 'sometimes|url',
            'event_date' => 'sometimes|date',
            'status' => 'sometimes|in:draft,active,scheduled,expired',
        ]);

        $concert = $service->update($musicConcert, $data);

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert updated'
        );
    }

    public function destroy(MusicConcert $musicConcert)
    {
        $musicConcert->delete();

        return ApiResponse::success(null, 'Concert deleted');
    }
}
