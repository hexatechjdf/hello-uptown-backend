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
    public function index(Request $request, MusicConcertService $service)
    {
        $concerts = $service->paginate($request->all());

        return ApiResponse::resource(
            MusicConcertResource::collection($concerts),
            'Music & concerts list'
        );
    }

    public function show(MusicConcert $musicConcert)
    {
        return ApiResponse::resource(
            new MusicConcertResource($musicConcert),
            'Concert details retrieved'
        );
    }

    public function store(Request $request, MusicConcertService $service)
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
            'time_json'           => 'nullable|array',
            'event_date'          => 'nullable|date',
            'status'              => 'required|in:draft,active,scheduled,expired',
            'featured'            => 'boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $concert = $service->store($validator->validated());

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert created'
        );
    }

    public function update(Request $request, MusicConcert $musicConcert, MusicConcertService $service)
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
            'time_json'           => 'sometimes|nullable|array',
            'event_date'          => 'sometimes|nullable|date',
            'status'              => 'sometimes|required|in:draft,active,scheduled,expired',
            'featured'            => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }
        $concert = $service->update($musicConcert, $validator->validated());

        return ApiResponse::resource(
            new MusicConcertResource($concert),
            'Concert updated'
        );
    }

    public function destroy(MusicConcert $musicConcert, MusicConcertService $service)
    {
        $service->delete($musicConcert);

        return ApiResponse::success(null, 'Concert deleted');
    }
}
