<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Deal;
use App\Repositories\Deal\DealRepository;
use App\Resources\Deal\DealResource;
use App\Services\Deal\DealService;

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
            'category_id' => $request->input('category_id'),
            'business_id' => $request->input('business_id'),
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
            'popularDeals' => 2,
            'expiringSoonDeals' => 2,
            'newDeals'  => 2,
        ], 'Deals retrieved successfully');
    }

    /**
     * Deal detail
     */
    public function show($id)
    {
        $deal = Deal::where('id', $id)->where('status', true)->first();
        if (!$deal) {
            return ApiResponse::error('Deal not found', 404);
        }
        return ApiResponse::collection(DealResource::collection(collect([$deal])), 'Deal fetched successfully');
    }
    public function dealOfTheWeek()
    {
        $mainDeal = $this->repo->getDealOfTheWeek();

        if (!$mainDeal) {
            return ApiResponse::error('No deal of the week found', 404);
        }
        $otherDeals = $this->repo->getOtherGreatDeals($mainDeal->id, 4);
        return ApiResponse::success([
            'dealOfTheWeek' => new DealResource($mainDeal),
            'otherGreatDeals' => DealResource::collection($otherDeals),
        ], 'Deal of the week retrieved successfully');
    }
}
