<?php

namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmerMarket;
use App\Repositories\Admin\FarmerMarket\FarmerMarketRepository;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\FarmerMarket\StoreFarmerMarketRequest;
use App\Http\Requests\Admin\FarmerMarket\UpdateFarmerMarketRequest;
use App\Resources\Admin\FarmerMarket\FarmerMarketResource;
use App\Services\Admin\FarmerMarket\FarmerMarketService;

class FarmerMarketController extends Controller
{
    public function index(Request $request)
    {
        $markets = app(FarmerMarketRepository::class)->paginate($request->all());

        return ApiResponse::collection(
            FarmerMarketResource::collection($markets),
            'Farmer markets list'
        );
    }

    public function store(StoreFarmerMarketRequest $request)
    {
        $market = app(FarmerMarketService::class)->store($request->validated());
        return ApiResponse::resource(
            new FarmerMarketResource($market),
            'Farmer market created'
        );
    }

    public function update(UpdateFarmerMarketRequest $request, FarmerMarket $farmerMarket)
    {
        $market = app(FarmerMarketService::class)->update($farmerMarket, $request->validated());

        return ApiResponse::resource(
            new FarmerMarketResource($market),
            'Farmer market updated'
        );
    }

    public function destroy(FarmerMarket $farmerMarket)
    {
        app(FarmerMarketRepository::class)->delete($farmerMarket);

        return ApiResponse::success(null, 'Farmer market deleted');
    }
}

?>
