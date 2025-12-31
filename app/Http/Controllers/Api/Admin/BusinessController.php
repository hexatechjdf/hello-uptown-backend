<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BusinessService;
use App\Resources\Admin\BusinessResource;
use App\Helpers\ApiResponse;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use App\Resources\User\UserResource;

use App\Resources\Admin\User\UserResource as AdminUserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function __construct(
        protected BusinessService $service
    ) {}

    public function getProfile(Request $request)
    {
        $business = $this->service->getByUserId($request->user()->id);

        return ApiResponse::resource(new BusinessResource($business->load('user')),'Business profile fetched successfully');
    }
    public function index(Request $request)
    {
        $businesses = $this->service->list($request->all());

        return ApiResponse::collection(
            BusinessResource::collection($businesses),
            'Businesses fetched successfully'
        );
    }

   public function store(Request $request)
    {
        $userId = $request->user_id;
        if (Business::where('user_id', $userId)->exists()) {
            return ApiResponse::error('Business already exists against this user');
        }
        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'business_name' => 'required|string|max:255',

            // Descriptions
            'short_description' => 'nullable|string',
            'long_description'  => 'nullable|string',
            'description'       => 'nullable|string',

            // Category & Tags
            'category_id' => 'nullable|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',

            // Branding
            'logo'        => 'nullable|string',
            'cover_image' => 'nullable|string',

            // Contact & Location
            'address'           => 'nullable|string',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'redemption_radius' => 'nullable|integer',
            'phone'             => 'nullable|string|max:50',
            'email'             => 'nullable|email',
            'website'           => 'nullable|url',
            'opening_hours'     => 'nullable|string',

            // Social Links
            'facebook_link'  => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'twitter_link'   => 'nullable|url',

            // Slider Settings
            'slider_tagline'           => 'nullable|string',
            'slider_section_text'      => 'nullable|string',
            'slider_heading_one'       => 'nullable|string',
            'slider_subheading'        => 'nullable|string',
            'slider_short_description' => 'nullable|string',
            'slider_image'             => 'nullable|string',
            'image_overlay_heading'    => 'nullable|string',
            'image_overlay_heading2'   => 'nullable|string',

            'slider_text1'       => 'nullable|string',
            'slider_text1_value' => 'nullable|string',
            'slider_text2'       => 'nullable|string',
            'slider_text2_value' => 'nullable|string',
            'slider_text3'       => 'nullable|string',
            'slider_text3_value' => 'nullable|string',

            'send_new_deals' => 'nullable|boolean',
            'status'         => 'nullable|boolean',
        ]);

        $slug = Str::slug($data['business_name']);
        if (Business::where('slug', $slug)->exists()) {
            return ApiResponse::error(
                'Business with this name already exists',
                422
            );
        }
        $data['slug'] = $slug;

        $business = $this->service->create($data);

        return ApiResponse::resource(
            new BusinessResource($business),
            'Business created successfully',
            [],
            201
        );
    }

    public function show(Business $business)
    {
        return ApiResponse::resource(
            new BusinessResource($business->load('user')),
            'Business details'
        );
    }
    public function update(Request $request, Business $business)
    {
        $data = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            // Descriptions
            'short_description' => 'sometimes|string',
            'long_description'  => 'sometimes|string',
            'description'       => 'sometimes|string',
            // Category & Tags
            'category_id' => 'sometimes|exists:categories,id',
            'tags'        => 'sometimes|array',
            'tags.*'      => 'string',

            // Branding
            'logo'        => 'sometimes|string',
            'cover_image' => 'sometimes|string',
            // Contact & Location
            'address'           => 'sometimes|string',
            'latitude'          => 'sometimes|numeric',
            'longitude'         => 'sometimes|numeric',
            'redemption_radius' => 'sometimes|integer',
            'phone'             => 'sometimes|string|max:50',
            'email'             => 'sometimes|email',
            'website'           => 'sometimes|url',
            'opening_hours'     => 'sometimes|string',

            // Social Links
            'facebook_link'  => 'sometimes|url',
            'instagram_link' => 'sometimes|url',
            'twitter_link'   => 'sometimes|url',

            // Slider Settings
            'slider_tagline'           => 'sometimes|string',
            'slider_section_text'      => 'sometimes|string',
            'slider_heading_one'       => 'sometimes|string',
            'slider_subheading'        => 'sometimes|string',
            'slider_short_description' => 'sometimes|string',
            'slider_image'             => 'sometimes|string',
            'image_overlay_heading'    => 'sometimes|string',
            'image_overlay_heading2'   => 'sometimes|string',

            'slider_text1'       => 'sometimes|string',
            'slider_text1_value' => 'sometimes|string',
            'slider_text2'       => 'sometimes|string',
            'slider_text2_value' => 'sometimes|string',
            'slider_text3'       => 'sometimes|string',
            'slider_text3_value' => 'sometimes|string',

            // Notification & Status
            'send_new_deals' => 'sometimes|boolean',
            'status'         => 'sometimes|boolean',
        ]);

            if (isset($data['business_name'])) {
            $slug = Str::slug($data['business_name']);
            $originalSlug = $slug;
            $count = 1;

            while (
                Business::where('slug', $slug)
                    ->where('id', '!=', $business->id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }
        // dd($data);
        $this->service->update($business, $data);
        $business->refresh();

        return ApiResponse::resource(
            new BusinessResource($business),
            'Business updated successfully'
        );
    }
    
    public function users(Request $request)
    {
        $users = User::get();
        return ApiResponse::success(AdminUserResource::collection($users),'User list retrieved');
    }

    public function user($id)
    {
        $user = User::find($id);
         return ApiResponse::resource(new AdminUserResource($user),'User information');
    }

    public function userDelete($id)
    {
        $user = User::find($id);
        if(!$user){
            return ApiResponse::error('User already deleted');
        }
        $user->delete();
         return ApiResponse::resource(new AdminUserResource($user),'User deeted successfully');
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return ApiResponse::success(null, 'Business deleted successfully');
    }

    public function userUpdate(Request $request)
    {
        if($request->has('user_id') && $request->user_id !== null){
            $user = User::find($request->user_id);
        }else{
            $user = $request->user();
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->profile = $validated['profile'] ?? $user->profile;
        $user->name = $validated['first_name'] . " " . $validated['last_name'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return ApiResponse::resource(new UserResource($user), 'User updated successfully');
    }

    public function userNotification(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'notifications' => ['required', 'string', 'max:255']
        ]);
        $user->notifications = $validated['notifications'];
        $user->save();
        return ApiResponse::resource(new UserResource($user), 'User Notification status updated successfully');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirm_new_password' => 'required|same:new_password'
            ],
            [
                'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
                'new_password.min' => 'Password must be at least 12 characters long.',
                'confirm_new_password.same' => 'New password and confirmation password do not match.'
            ]);

            $user = $request->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect.',
                    'errors' => [
                        'current_password' => ['Current password is incorrect.']
                    ]
                ], 422);
            }
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'message' => 'New password must be different from current password.',
                    'errors' => [
                        'new_password' => ['New password must be different from current password.']
                    ]
                ], 422);
            }
            $user->password = $request->new_password;
            $user->save();

            return ApiResponse::resource(new UserResource($user), 'Password updated successfully');
        }

}
