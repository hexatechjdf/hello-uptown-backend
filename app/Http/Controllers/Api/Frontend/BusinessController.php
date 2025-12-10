<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Business\BusinessRepository;
use App\Services\Business\BusinessService;
use App\Http\Resources\BusinessResource;
use App\Helpers\ApiResponse;

class BusinessController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(BusinessService $service, BusinessRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }

    /**
     * List all businesses with search, filter, sort
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'filter' => $request->input('filter'), // featured, mostPopular, newest
            'status' => $request->input('status'), // active, inactive
        ];

        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $perPage = $request->input('perPage', 10);

        $businesses = $this->repo->all($filters, $sort, $order, $perPage);

        // Additional card info
        $popularBusinesses = $this->repo->getPopularBusinesses(5);
        $featuredBusinesses = $this->repo->getFeaturedBusinesses(5);

        return ApiResponse::success([
            'businesses' => BusinessResource::collection($businesses),
            'popularBusinesses' => BusinessResource::collection($popularBusinesses),
            'featuredBusinesses' => BusinessResource::collection($featuredBusinesses),
        ], 'Businesses retrieved successfully');
    }

    /**
     * Business detail
     */
    public function show($id)
    {
        $business = $this->repo->find($id);
        return ApiResponse::resource(new BusinessResource($business));
    }
}
