<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;

class LoginService
{
    protected $authRepo;

    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function login(array $data)
    {
        $user = $this->authRepo->login($data);

        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
