<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Resources\User\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use App\Services\Auth\RegisterService;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    protected $registerService;
    protected $loginService;

    public function __construct(RegisterService $registerService, LoginService $loginService)
    {
        $this->registerService = $registerService;
        $this->loginService = $loginService;
    }
    public function register(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:50',
            'last_name'        => 'required|string|max:50',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'sometimes|string|max:20',
            'password'         => 'required|confirmed',
            'role'             => 'required|exists:roles,name',
            'business_name'    => 'required_if:role,business_admin|string|max:255',
        ]);

        $user = $this->registerService->register($request->all());
        $token = $user->createToken('api_token')->plainTextToken;
        return ApiResponse::resource(new UserResource($user), 'User registered successfully', ['token' => $token]);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->loginService->login($data);
            $user = $result['user'];
            $token = $result['token'];
            return ApiResponse::resource(new UserResource($user), 'Login successful', ['token' => $token]);
        } catch (\Exception $e) {
            return ApiResponse::error('Invalid credentials', null, 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
