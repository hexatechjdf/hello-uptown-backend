<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DealResource;
use App\Models\Business;
use App\Models\Deal;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Resources\Business\BusinessResource;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $carouselBusinesses = Business::where('status', true)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $topRatedPartners = Business::where('status', true)
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        $topDealsOfMonth = Deal::where('status', true)
            ->whereMonth('created_at', now()->month)
            ->take(5)
            ->get();

        $browseCategories = Category::all();

        $featuredBusinesses = Business::where('featured', true)
            ->take(5)
            ->get();

        $contactUs = [
            'email' => config('site.contact_email', 'info@hellouptown.com'),
            'phone' => config('site.contact_phone', '+1 234 567 890'),
            'address' => config('site.contact_address', '123 Main Street'),
        ];

        $newsletter = [
            'title' => 'Subscribe to our newsletter',
            'description' => 'Get the latest deals and updates directly in your inbox.',
        ];

        // 8️⃣ Footer - static config
        $footer = [
            'copyright' => '© ' . date('Y') . ' HelloUptown. All rights reserved.',
            'socials' => [
                'facebook' => config('site.facebook'),
                'instagram' => config('site.instagram'),
                'twitter' => config('site.twitter'),
            ],
        ];

        return ApiResponse::success([
            'carouselBusinesses' => BusinessResource::collection($carouselBusinesses),
            'topRatedPartners' => BusinessResource::collection($topRatedPartners),
            'topDealsOfMonth' => DealResource::collection($topDealsOfMonth),
            'browseCategories' => $browseCategories,
            'featuredBusinesses' => BusinessResource::collection($featuredBusinesses),
            'contactUs' => $contactUs,
            'newsletter' => $newsletter,
            'footer' => $footer,
        ], 'Home page data fetched successfully');
    }
}
