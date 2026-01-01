<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Deal;
use App\Repositories\Website\DealRepository; // Changed this
use App\Resources\Deal\DealResource;
use App\Resources\Deal\DealOfWeekResource;
use App\Services\Deal\DealService;

class DealController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(DealService $service, DealRepository $repo) // Type-hint with Website\DealRepository
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
        $perPage = $request->input('perPage', 0);

        $deals = $this->repo->all($filters, $sort, $order, $perPage);

        // Additional 2 card info
        $popularDeals = $this->repo->getPopularDeals(5)->count();
        $expiringSoonDeals = $this->repo->getExpiringSoonDeals(5)->count();
        $getNewestDeals = $this->repo->getNewestDeals(5)->count();

        return ApiResponse::success([
            'deals' => DealResource::collection($deals),
            'popularDeals' => $popularDeals,
            'expiringSoonDeals' => $expiringSoonDeals,
            'newDeals'  => $getNewestDeals,
        ], 'Deals retrieved successfully');
    }

    /**
     * Deal detail
     */
    public function show($id)
    {
        $deal = $this->repo->findActive($id); // Use repository method

        if (!$deal) {
            return ApiResponse::error('Deal not found', 404);
        }

        return ApiResponse::success(
            new DealResource($deal),
            'Deal fetched successfully'
        );
    }

    /**
     * Deal of the week
     */
    public function dealOfTheWeek()
    {
        $mainDeal = $this->repo->getDealOfTheWeek();

        if (!$mainDeal) {
            return ApiResponse::error('No deal of the week found', 404);
        }

        $otherDeals = $this->repo->getOtherGreatDeals($mainDeal->id, 4);

        return ApiResponse::success(
            new DealOfWeekResource($mainDeal),
            'Deal of the week retrieved successfully'
        );
    }

    /**
     * Top deals of the month
     */
    public function topDealsOfMonth()
    {
        $deals = $this->repo->topDealsOfMonth(5);

        return ApiResponse::success(
            DealResource::collection($deals),
            'Top deals of the month fetched successfully'
        );
    }
}
