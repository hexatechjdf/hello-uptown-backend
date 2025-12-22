<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\DealResource;
use App\Models\Business;
use App\Models\Deal;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Resources\Website\BusinessResource;
use App\Resources\Deal\DealResource as DealDealResource;
use App\Resources\User\UserResource;

class HomeController extends Controller
{
    public function carouselBusinesses()
    {
        $businesses = Business::where('status', true)->orderByDesc('created_at')->take(5)->get();
        return ApiResponse::collection(BusinessResource::collection($businesses),'Carousel businesses fetched successfully');
    }
    public function topRatedPartners()
    {
        $businesses = Business::where('status', true)->take(4)->get();
        // ->orderByDesc('rating')
        return ApiResponse::collection(BusinessResource::collection($businesses),'Top rated partners fetched successfully');
    }
    public function topDealsOfMonth()
    {
        $deals = Deal::where('status', true)->whereMonth('created_at', now()->month)->take(5)->get();
        return ApiResponse::collection(DealDealResource::collection($deals),'Top deals of the month fetched successfully');
    }
    public function browseCategories()
    {
        $categories = Category::all();

        return ApiResponse::collection(
            $categories,
            'Categories fetched successfully'
        );
    }


    // public function allDeals(Request $request)
    // {
    //     $search = $request->input('search'); // Get search query from request
    //     $dealsQuery = Deal::query()->where('status', true);
    //     if ($search) {
    //         $dealsQuery->where(function ($query) use ($search) {
    //             $query->where('title', 'like', "%{$search}%")
    //                 ->orWhere('short_description', 'like', "%{$search}%")
    //                 ->orWhere('long_description', 'like', "%{$search}%")
    //                 ->orWhere('discount', 'like', "%{$search}%")
    //                 ->orWhere('original_price', 'like', "%{$search}%")
    //                 ->orWhere('terms_conditions', 'like', "%{$search}%");
    //         });
    //     }
    //     $deals = $dealsQuery->orderBy('created_at', 'desc')->get();

    //     return ApiResponse::collection(DealDealResource::collection($deals), 'All deals fetched successfully');
    // }

    // public function getDeal($id)
    // {
    //     $deal = Deal::where('id', $id)->where('status', true)->first();

    //     if (!$deal) {
    //         return ApiResponse::error('Deal not found', 404);
    //     }
    //     return ApiResponse::collection(DealDealResource::collection(collect([$deal])), 'Deal fetched successfully');
    // }
    public function featuredBusinesses()
    {
        $businesses = Business::take(5)->get();
        // where('featured', true)
        return ApiResponse::collection(BusinessResource::collection($businesses),'Featured businesses fetched successfully');
    }
    public function contactUs()
    {
        $contactUs = [
            'email' => config('site.contact_email', 'info@hellouptown.com'),
            'phone' => config('site.contact_phone', '+1 234 567 890'),
            'address' => config('site.contact_address', '123 Main Street
            Downtown District
            City, State 12345'),
            'business_hours' => config('site.business_hours', 'Mon - Fri: 9:00 AM - 6:00 PM
            Sat: 10:00 AM - 4:00 PM
            Sun: Closed'),
        ];
        return ApiResponse::success(
            $contactUs,
            'Contact us information fetched successfully'
        );
    }
    public function newsletter()
    {
        $newsletter = [
            'title' => 'Subscribe to our newsletter',
            'description' => 'Get the latest deals and updates directly in your inbox.',
        ];

        return ApiResponse::success(
            $newsletter,
            'Newsletter information fetched successfully'
        );
    }
    public function footer()
    {
        $footer = [
            'copyright' => '© ' . date('Y') . ' HelloUptown. All rights reserved.',
            'socials' => [
                'facebook' => config('site.facebook'),
                'instagram' => config('site.instagram'),
                'twitter' => config('site.twitter'),
            ],
        ];
        return ApiResponse::success(
            $footer,
            'Footer information fetched successfully'
        );
    }
    public function GetMyInfo(Request $request)
    {
        $user  = $request->user();
        return ApiResponse::resource(new UserResource($user),'User Fetched Successfully');
    }
    public function activeStats()
    {
        $activeUsersCount = User::where('status', true)->count();
        $activeBusinessesCount = Business::where('status', true)->count();
        $data = [
            'active_users'     => $activeUsersCount,
            'active_businesses'=> $activeBusinessesCount,
        ];

        return ApiResponse::success($data, 'Active users and businesses fetched successfully');
    }

}
