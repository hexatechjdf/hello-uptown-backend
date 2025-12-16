<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Resources\User\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use App\Services\Auth\RegisterService;
use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    protected $registerService;
    protected $loginService;

    public function __construct(RegisterService $registerService, LoginService $loginService)
    {
        $this->registerService = $registerService;
        $this->loginService = $loginService;
    }
    public function register(RegisterUserRequest $request)
    {
        $input = $request->validated();
        $user = $this->registerService->register($input);
        $token = $user->createToken('api_token')->plainTextToken;
        return ApiResponse::resource(new UserResource($user->load('business')), 'User registered successfully', ['token' => $token]);
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
            return ApiResponse::resource(new UserResource($user->load('business')), 'Login successful', ['token' => $token]);
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
