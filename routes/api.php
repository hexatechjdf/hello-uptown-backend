<?php

use App\Http\Controllers\Api\Admin\ArtFairController;
use App\Http\Controllers\Api\Admin\BusinessController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\DiningController;
use App\Http\Controllers\Api\Admin\FarmerMarketController;
use App\Http\Controllers\Api\Admin\HappyHourController;
use App\Http\Controllers\Api\Admin\HealthWellnessController;
use App\Http\Controllers\Api\Admin\MusicConcertController;
use App\Http\Controllers\Api\Admin\NewsController;
use App\Http\Controllers\Api\Admin\NightlifeController;
use App\Http\Controllers\Api\Admin\PorchfestController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Coupon\CouponController as ApiCouponController;
use App\Http\Controllers\Api\Deal\DealController as ApiDealController;
use App\Http\Controllers\Api\Frontend\DealController;
use App\Http\Controllers\Api\Frontend\CouponController;
use App\Http\Controllers\Api\Frontend\ContactMessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Redemption\RedemptionController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return response()->json(['message' => 'API is running']);
});

// website routes
Route::prefix('home')->group(function () {
    Route::get('/carousel-businesses', [HomeController::class, 'carouselBusinesses']);
    Route::get('/top-rated-partners', [HomeController::class, 'topRatedPartners']);
    Route::get('/top-deals-of-month', [HomeController::class, 'topDealsOfMonth']);
    Route::get('/all-deals', [HomeController::class, 'allDeals']);
    Route::get('/deal/{id}', [HomeController::class, 'getDeal']);
    Route::get('/browse-categories', [HomeController::class, 'browseCategories']);
    Route::get('/featured-businesses', [HomeController::class, 'featuredBusinesses']);
    Route::get('/contact-us', [HomeController::class, 'contactUs']);
    Route::get('/newsletter', [HomeController::class, 'newsletter']);
    Route::get('/footer', [HomeController::class, 'footer']);
});

Route::get('/deals', [DealController::class, 'index']);
Route::get('/deals/{id}', [DealController::class, 'show']);
Route::get('/businesses', [BusinessController::class, 'index']);
Route::get('/businesses/{id}', [BusinessController::class, 'show']);
Route::get('/deals/week', [DealController::class, 'dealOfTheWeek']);
Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/{id}', [CouponController::class, 'show']);
Route::post('/contact-us', [ContactMessageController::class, 'submit']);


Route::get('/me', [HomeController::class, 'GetMyInfo'])->middleware('auth:sanctum');
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});



Route::middleware(['auth:sanctum', 'role:business_admin'])->group(function () {
    Route::prefix('business')->group(function () {

        Route::get('/profile', [BusinessController::class, 'getProfile']);
        Route::put('/profile/{business}', [BusinessController::class, 'update']);
        Route::apiResource('deals', ApiDealController::class);
        Route::apiResource('coupons', ApiCouponController::class);
        Route::get('/redemptions', [RedemptionController::class, 'index']);

        Route::prefix('/dashboard')->group(function () {
            Route::get('/metrics', [DashboardController::class, 'metrics']);
            Route::get('/top-coupons', [DashboardController::class, 'topCoupons']);
            Route::get('/recent-redemptions', [DashboardController::class, 'recentRedemptions']);
        });
    });
});

// Admin Routes
Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:superadmin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::apiResource('businesses', BusinessController::class);
        Route::apiResource('music-concerts', MusicConcertController::class);
        Route::apiResource('farmer-markets', FarmerMarketController::class);
        Route::apiResource('art-fairs', ArtFairController::class);
        Route::apiResource('porchfests', PorchfestController::class);
        Route::apiResource('dinings', DiningController::class);
        Route::apiResource('nightlifes', NightlifeController::class);
        Route::apiResource('health-wellness', HealthWellnessController::class);
        Route::apiResource('happy-hours', HappyHourController::class);
        Route::apiResource('news', NewsController::class);
    });
