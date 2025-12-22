<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepository;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'] ?? null,
                'password'   => $data['password'],
            ]);

            // Attach Role
            $role = Role::where('name', $data['role'])->first();
            $user->roles()->attach($role->id);

            // Business Admin → Create Business
            if ($data['role'] === 'business_admin') {
                $business = $user->business()->create([
                    'business_name' => $data['business_name'],
                    'slug' => Str::slug($data['business_name']),
                ]);
                $user->business_id = $business->id;
                $user->Save();
            }

            return $user;
        });
    }
}
