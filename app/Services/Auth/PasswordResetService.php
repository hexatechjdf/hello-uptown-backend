<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class PasswordResetService
{
    public function sendResetLink($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('User not found with this email');
        }
        $encryptedUserId = Crypt::encryptString($user->id);
        $resetUrl = "http://localhost:3000/reset-password?token=".$encryptedUserId;
        Mail::to($user->email)->send(new PasswordResetMail($resetUrl, $user));

        return true;
    }

    public function resetPassword($userId, $password)
    {
        $user = User::findOrFail($userId);
        if (!$user) {
            throw new \Exception('User not found with this email');
        }
        $user->password = Hash::make($password);
        $user->save();
        // Optionally, you can revoke all tokens to force re-login
        // $user->tokens()->delete();
        return $user;
    }
}
