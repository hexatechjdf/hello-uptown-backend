<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DealResource;
use App\Helpers\ApiResponse;
use App\Repositories\DealRepository;
use App\Services\DealService;

class DealController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(DealService $service, DealRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }

    /**
     * List all deals with filters, search, sorting
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'filter' => $request->input('filter'), // mostPopular, newest, expiringSoon
        ];

        $sort = 'created_at';
        $order = 'desc';
        $perPage = $request->input('perPage', 10);

        $deals = $this->repo->all($filters, $sort, $order, $perPage);

        // Additional 2 card info
        $popularDeals = $this->repo->getPopularDeals(5);
        $expiringSoonDeals = $this->repo->getExpiringSoonDeals(5);

        return ApiResponse::success([
            'deals' => DealResource::collection($deals),
            'popularDeals' => DealResource::collection($popularDeals),
            'expiringSoonDeals' => DealResource::collection($expiringSoonDeals),
        ], 'Deals retrieved successfully');
    }

    /**
     * Deal detail
     */
    public function show($id)
    {
        $deal = $this->repo->find($id);
        return ApiResponse::resource(new DealResource($deal));
    }
    public function dealOfTheWeek()
    {
        $mainDeal = $this->repo->getDealOfTheWeek();

        if (!$mainDeal) {
            return ApiResponse::error('No deal of the week found', 404);
        }

        // 2️⃣ Other 4 great deals (excluding main deal)
        $otherDeals = $this->repo->getOtherGreatDeals($mainDeal->id, 4);

        return ApiResponse::success([
            'dealOfTheWeek' => new \App\Http\Resources\DealResource($mainDeal),
            'otherGreatDeals' => \App\Http\Resources\DealResource::collection($otherDeals),
        ], 'Deal of the week retrieved successfully');
    }
}
