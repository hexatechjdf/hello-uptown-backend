<?php
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