<?php

namespace App\Http\Services;

use App\Mail\UserLoginOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OtpService
{
    public function generate($user, $purpose = 'login')
    {
        $code = rand(100000, 999999);
        $cacheKey = "otp_{$user->id}_{$purpose}";

        Cache::put($cacheKey, Hash::make($code), now()->addMinutes(5));

        if ($user->email) {
            Mail::to($user->email)->send(new UserLoginOtp($code));
        }

        return $code;
    }

    public function verify($user, $code, $purpose = 'login')
    {
        $cacheKey = "otp_{$user->id}_{$purpose}";
        $hashedCode = Cache::get($cacheKey);

        if ($hashedCode && Hash::check($code, $hashedCode)) {
            Cache::forget($cacheKey);
            return true;
        }

        return false;
    }
}
