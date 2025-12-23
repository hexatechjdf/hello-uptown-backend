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
    public function index(Request $request)
    {
        $query = MusicConcert::query();

        if ($request->search) {
            $query->where('main_heading', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

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
            'sub_heading'         => 'nullable|string',
            'event_description'   => 'required|string',
            'image'               => 'nullable|url', // URL validation
            'available_attendees' => 'nullable|integer',
            'address'             => 'nullable|string',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'website'             => 'nullable|url',
            'event_date'          => 'nullable|date',
            'status'              => 'required|in:draft,active,scheduled,expired',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error(
                'Validation failed',
                $validator->errors(),
                422
            );
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
            'sub_heading'         => 'sometimes|nullable|string',
            'event_description'   => 'sometimes|required|string',
            'image'               => 'sometimes|nullable|url', // URL validation
            'available_attendees' => 'sometimes|nullable|integer',
            'address'             => 'sometimes|nullable|string',
            'latitude'            => 'sometimes|nullable|numeric',
            'longitude'           => 'sometimes|nullable|numeric',
            'website'             => 'sometimes|nullable|url',
            'event_date'          => 'sometimes|nullable|date',
            'status'              => 'sometimes|required|in:draft,active,scheduled,expired',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error(
                'Validation failed',
                $validator->errors(),
                422
            );
        }
        $concert = $service->update($musicConcert, $validator->validated());

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
