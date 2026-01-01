<?php

namespace App\Http\Controllers\Api\Frontend\AllPages;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\MusicConcert;
use App\Models\Advertisement;
use App\Repositories\Admin\ArtFair\ArtFairRepository;
use App\Repositories\Admin\FarmerMarket\FarmerMarketRepository;
use App\Repositories\Admin\Porchfest\PorchfestRepository;
use App\Services\Admin\Dining\DiningService;
use App\Services\Admin\HappyHour\HappyHourService;
use App\Services\Admin\HealthWellness\HealthWellnessService;
use App\Services\Admin\News\NewsService;
use App\Services\Admin\Nightlife\NightlifeService;
use App\Resources\Website\AllPages\ArtFair\ArtFairResource;
use App\Resources\Website\AllPages\Dining\DiningResource;
use App\Resources\Website\AllPages\FarmerMarket\FarmerMarketResource;
use App\Resources\Website\AllPages\HappyHour\HappyHourResource;
use App\Resources\Website\AllPages\HealthWellness\HealthWellnessResource;
use App\Resources\Website\AllPages\MusicConcert\MusicConcertResource;
use App\Resources\Website\AllPages\News\NewsResource;
use App\Resources\Website\AllPages\NightLife\NightlifeResource;
use App\Resources\Website\AllPages\Porchfest\PorchfestResource;
use App\Resources\Website\AllPages\Advertisement\AdvertisementResource;

class AllPagesController extends Controller
{
    public function musicConcerts(Request $request) {
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

    public function farmerMarkets(Request $request) {
        $markets = app(FarmerMarketRepository::class)->paginate($request->all());

        return ApiResponse::collection(
            FarmerMarketResource::collection($markets),
            'Farmer markets list'
        );
    }

    public function artFairs(Request $request) {
        $repo = app(ArtFairRepository::class);
        $data = $repo->list($request->all());

        return ApiResponse::collection(
            ArtFairResource::collection($data),
            'Art fairs list'
        );
    }

    public function porchfests(Request $request) {
        $repo = app(PorchfestRepository::class);
        $list = $repo->list($request->all());

        return ApiResponse::collection(
            PorchfestResource::collection($list),
            'Porchfest list'
        );
    }

    public function dinings(Request $request) {
        $service = app(DiningService::class);

        $dinings = $service->getAll(
            $request->only(['search', 'status', 'category_id', 'featured', 'price_range']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            DiningResource::collection($dinings),
            'Dining list retrieved'
        );
    }

    public function nightlifes(Request $request) {
        $service = app(NightlifeService::class);

        $nightlifes = $service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            NightlifeResource::collection($nightlifes),
            'Nightlife list retrieved'
        );
    }

    public function healthWellness(Request $request) {
        $service = app(HealthWellnessService::class);

        $items = $service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            HealthWellnessResource::collection($items),
            'Health & Wellness list retrieved'
        );
    }

    public function happyHours(Request $request) {
        $service = app(HappyHourService::class);

        $items = $service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'created_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            HappyHourResource::collection($items),
            'Happy Hours list retrieved'
        );
    }

    public function news(Request $request) {
        $service = app(NewsService::class);

        $items = $service->all(
            $request->only(['search', 'status', 'category_id', 'featured']),
            $request->get('sort', 'published_at'),
            $request->get('order', 'desc'),
            $request->get('perPage', 10)
        );

        return ApiResponse::collection(
            NewsResource::collection($items),
            'News list retrieved'
        );
    }

    public function advertisements(Request $request) {
        $allAdvertisements = Advertisement::get();
        return ApiResponse::collection(AdvertisementResource::collection($allAdvertisements), 'Advertisement retrieved');
    }
}
