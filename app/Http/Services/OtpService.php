<?php

namespace App\Http\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoginOtp;
class OtpService
{
    public function generate($user, $purpose = 'login')
    {
        $code = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'target' => $user->email ?? $user->phone,
            'code' => Hash::make($code),
            'purpose' => $purpose,
        ]);
        if ($user->email) {
            Mail::to($user->email)->send(new UserLoginOtp($code));
          }
        return $code; 
    }

    public function verify($user, $code, $purpose = 'login')
    {
        $otp = Otp::where('user_id', $user->id)
        ->where('purpose', $purpose)
        ->latest()
        ->first();

        if (!$otp) return false;

        return Hash::check($code, $otp->code);
    }
}
