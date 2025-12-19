<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Resources\User\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use App\Services\Auth\RegisterService;
use App\Helpers\ApiResponse;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\PasswordResetService;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    protected $registerService;
    protected $loginService;
    protected $passwordResetService;

    public function __construct(RegisterService $registerService, LoginService $loginService, PasswordResetService $passwordResetService)
    {
        $this->registerService = $registerService;
        $this->loginService = $loginService;
        $this->passwordResetService = $passwordResetService;
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
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $resp = $this->passwordResetService->sendResetLink($validated['email']);
        return ApiResponse::success($resp, 'Password reset link has been sent to your email. Please check your inbox and follow the instructions.');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to send reset link. Please try again later.' . $e->getMessage(),
                null,
                500
            );
        }
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $decryptedUserId = Crypt::decryptString($validated["token"]);
            $this->passwordResetService->resetPassword($decryptedUserId,$validated['password']);

        return ApiResponse::success(null, 'Password has been reset successfully. You can now login with your new password.');

        } catch (\Exception $e) {
            return ApiResponse::error('Failed to reset password. Please try again - '. $e->getMessage(),null,500);
        }
    }
}
