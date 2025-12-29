<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Expose-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");

use App\Http\Controllers\Api\Admin\ArtFairController;
use App\Http\Controllers\Api\Admin\BusinessController;
use App\Http\Controllers\Api\Frontend\BusinessController as FrontendBusinessController;
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
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Coupon\CouponController as ApiCouponController;
use App\Http\Controllers\Api\Deal\DealController as ApiDealController;
use App\Http\Controllers\Api\Frontend\DealController;
use App\Http\Controllers\Api\Frontend\CouponController;
use App\Http\Controllers\Api\Frontend\ContactMessageController;
use App\Http\Controllers\Api\Frontend\AllPages\AllPagesController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Frontend\SubscribrNewsLetterController;
use App\Http\Controllers\Api\Redemption\RedemptionController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return response()->json(['message' => 'API is running']);
});

// website routes
Route::prefix('home')->group(function () {
    Route::get('/carousel-businesses', [HomeController::class, 'carouselBusinesses']);
    Route::get('/top-rated-partners', [HomeController::class, 'topRatedPartners']);
    Route::get('/top-deals-of-month', [HomeController::class, 'topDealsOfMonth']);
    Route::get('/browse-categories', [HomeController::class, 'browseCategories']);
    Route::get('/featured-businesses', [HomeController::class, 'featuredBusinesses']);
    Route::get('/contact-us', [HomeController::class, 'contactUs']);
    Route::get('/newsletter', [HomeController::class, 'newsletter']);
    Route::get('/footer', [HomeController::class, 'footer']);

    // Route::get('/all-deals', [HomeController::class, 'allDeals']);
    // Route::get('/deal/{id}', [HomeController::class, 'getDeal']);
});

Route::get('/active-stats', [HomeController::class, 'activeStats']);
Route::get('/deals', [DealController::class, 'index']);
Route::get('/deals/{id}', [DealController::class, 'show']);
Route::get('/deals-week', [DealController::class, 'dealOfTheWeek']);
Route::get('/businesses', [FrontendBusinessController::class, 'index']);
Route::get('/businesses/{id}', [FrontendBusinessController::class, 'show']);

Route::get('music-concerts', [AllPagesController::class, 'musicConcerts']);
Route::get('farmer-markets', [AllPagesController::class, 'farmerMarkets']); // Done
Route::get('art-fairs', [AllPagesController::class, 'artFairs']); // Done
Route::get('porchfests', [AllPagesController::class, 'porchfests']); // Done
Route::get('dinings', [AllPagesController::class, 'dinings']); // Done
Route::get('nightlifes', [AllPagesController::class, 'nightlifes']); // Done
Route::get('health-wellness', [AllPagesController::class, 'healthWellness']);
Route::get('happy-hours', [AllPagesController::class, 'happyHours']);
Route::get('news', [AllPagesController::class, 'news']);

//Check this
Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/{id}', [CouponController::class, 'show']);

Route::post('/contact-message', [ContactMessageController::class, 'store']);
Route::post('/newsletter-subscribe', [SubscribrNewsLetterController::class, 'subscribe']);
Route::get('/unsubscribe/{email}', [SubscribrNewsLetterController::class, 'unsubscribe'])->name('unsubscribe');

// Route::post('/contact-us', [ContactMessageController::class, 'submit']);


Route::get('/me', [HomeController::class, 'GetMyInfo'])->middleware('auth:sanctum');
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
     Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('validate-reset-token/{token}', [AuthController::class, 'validateResetToken']);

});

Route::middleware(['auth:sanctum', 'check_business_id:business_admin,superadmin' ])->group(function () {
 Route::prefix('business')->group(function () {
        Route::apiResource('deals', ApiDealController::class);
        Route::get('/deal-stats', [ApiDealController::class, 'dealStats']);
        Route::apiResource('coupons', ApiCouponController::class);
        Route::get('/coupon-stats', [ApiCouponController::class, 'couponStats']);
        Route::get('/redemptions', [RedemptionController::class, 'index']);
        Route::get('/redemption-stats', [RedemptionController::class, 'RedemptionStats']);
    });
});


Route::middleware(['auth:sanctum', 'role:business_admin'])->group(function () {
    Route::prefix('business')->group(function () {

        Route::get('/profile', [BusinessController::class, 'getProfile']);
        Route::post('/user-update', [BusinessController::class, 'userUpdate']);
        Route::post('/user-notifications', [BusinessController::class, 'userNotification']);
        Route::post('/password-update', [BusinessController::class, 'passwordUpdate']);
        Route::put('/profile/{business}', [BusinessController::class, 'update']);
        // Route::apiResource('deals', ApiDealController::class);
        // Route::get('/deal-stats', [ApiDealController::class, 'dealStats']);
        // Route::apiResource('coupons', ApiCouponController::class);
        // Route::get('/coupon-stats', [ApiCouponController::class, 'couponStats']);
        Route::apiResource('categories', CategoryController::class);
        // Route::get('/redemptions', [RedemptionController::class, 'index']);
        // Route::get('/redemption-stats', [RedemptionController::class, 'RedemptionStats']);

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
