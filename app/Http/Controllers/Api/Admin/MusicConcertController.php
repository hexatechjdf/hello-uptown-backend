<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MusicConcert;
use App\Services\Admin\MusicConcertService;
use App\Resources\Admin\MusicConcertResource;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;

class MusicConcertController extends Controller
{
    protected $service;

    public function __construct(MusicConcertService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Get paginated items with consistent parameter names
        $items = $this->service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'event_date'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            MusicConcertResource::collection($items),
            'Music & concerts list retrieved'
        );
    }

    public function show(MusicConcert $musicConcert)
    {
        return ApiResponse::resource(
            new MusicConcertResource($musicConcert),
            'Concert details retrieved'
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'main_heading'        => 'required|string|max:255',
            'category_id'         => 'required|exists:categories,id',
            'artist'              => 'nullable|string|max:255',
            'event_description'   => 'required|string',
            'image'               => 'nullable|url',
            'available_attendees' => 'nullable|integer',
            'price'               => 'nullable|numeric',
            'address'             => 'nullable|string',
            'direction_link'      => 'nullable|url',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'website'             => 'nullable|url',
            'ticket_link'         => 'nullable|url',
            'time_json'           => 'nullable|json',
            'event_date'          => 'nullable|date',
            'status'              => 'required|in:draft,active,scheduled,expired',
            'featured'            => 'boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $concert = $this->service->store($validator->validated());

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert created successfully'
        );
    }

    public function update(Request $request, MusicConcert $musicConcert)
    {
        $validator = Validator::make($request->all(), [
            'main_heading'        => 'sometimes|required|string|max:255',
            'category_id'         => 'sometimes|required|exists:categories,id',
            'artist'              => 'sometimes|nullable|string|max:255',
            'event_description'   => 'sometimes|required|string',
            'image'               => 'sometimes|nullable|url',
            'available_attendees' => 'sometimes|nullable|integer',
            'price'               => 'sometimes|nullable|numeric',
            'address'             => 'sometimes|nullable|string',
            'direction_link'      => 'sometimes|nullable|url',
            'latitude'            => 'sometimes|nullable|numeric',
            'longitude'           => 'sometimes|nullable|numeric',
            'website'             => 'sometimes|nullable|url',
            'ticket_link'         => 'sometimes|nullable|url',
            'time_json'           => 'sometimes|nullable|json',
            'event_date'          => 'sometimes|nullable|date',
            'status'              => 'sometimes|required|in:draft,active,scheduled,expired',
            'featured'            => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $concert = $this->service->update($musicConcert, $validator->validated());

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert updated successfully'
        );
    }

    public function destroy(MusicConcert $musicConcert)
    {
        $this->service->delete($musicConcert);

        return ApiResponse::success(null, 'Concert deleted successfully');
    }
}
