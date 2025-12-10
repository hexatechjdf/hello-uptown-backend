<?php

use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\DiningController;
use App\Http\Controllers\Api\Admin\HappyHourController;
use App\Http\Controllers\Api\Admin\HealthWellnessController;
use App\Http\Controllers\Api\Admin\MusicConcertController;
use App\Http\Controllers\Api\Admin\NewsController;
use App\Http\Controllers\Api\Admin\NightlifeController;
use App\Http\Controllers\Api\Admin\PorchfestController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Business\BusinessController;
use App\Http\Controllers\Api\CouponController as ApiCouponController;
use App\Http\Controllers\Api\DealController as ApiDealController;
use App\Http\Controllers\Api\Frontend\DealController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Frontend\CouponController;
use App\Http\Controllers\Api\Frontend\ContactMessageController;
use App\Http\Controllers\Api\V1\Business\DashboardController;
use App\Http\Controllers\Api\V1\Business\RedemptionController;
use Illuminate\Support\Facades\Route;



Route::get('/home', [HomeController::class, 'index']);
Route::get('/deals', [DealController::class, 'index']);
Route::get('/deals/{id}', [DealController::class, 'show']);
Route::get('/businesses', [BusinessController::class, 'index']);
Route::get('/businesses/{id}', [BusinessController::class, 'show']);
Route::get('/deals/week', [DealController::class, 'dealOfTheWeek']);
Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/{id}', [CouponController::class, 'show']);
Route::post('/contact-us', [ContactMessageController::class, 'submit']);



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'role:business_admin'])->group(function () {
    Route::prefix('business')->group(function () {

        Route::put('/profile', [BusinessController::class, 'update']);
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
