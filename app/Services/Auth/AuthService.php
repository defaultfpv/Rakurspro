<?php

namespace App\Services\Auth;

use App\Models\Project;
use App\Traits\TokenMetadata;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class AuthService
{

    public function login($email, $password) {
        $user = User::where('email', $email)->first();
        if (!$user) return false;
        if ($user->password !== $password) return false;
        $token = Str::random(40);
        User::where('email', $email)->update(['access_token' => $token]);
        return $token;
    }

    public function logout($user) {
        return User::where("id", $user->id)->update(['access_token' => null]);
    }
}