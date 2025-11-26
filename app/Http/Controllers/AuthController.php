<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\UserLoginRequest;
use App\Http\Services\OtpService;
use App\Mail\UserLoginOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function user_register(UserRegisterRequest $request)
    {
        $input = $request->only(['name', 'email', 'password', 'department']);
        $input['password'] = bcrypt($input['password']);
        $input['activation_token'] = Str::random(60);
        $user = User::create($input);
        $user->assignRole('citizen');
        $data = [
            'user' => new RegisterResource($user)
        ];
        return $this->sendResponse($data, "User Registered Successfully");
    }

    public function citizen_login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Incorrect email or password', [], 401);
        }

        if ($request->has('fcm_token') && !empty($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        $purpose = $user->hasAnyRole(['citizen']);

        $otpCode = $this->otpService->generate($user, $purpose);
        Mail::to($user->email)->send(new UserLoginOtp($otpCode));
        return $this->sendResponse([
            'email' => $user->email,
            'purpose' => $purpose,
            'message' => 'OTP sent to your email'
        ], "Password Verified — Awaiting OTP");
    }
    public function employee_login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Incorrect email or password', [], 401);
        }
        if ($request->has('fcm_token') && !empty($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        $purpose = $user->hasAnyRole(['employee']);
        $otpCode = $this->otpService->generate($user, $purpose);
        Mail::to($user->email)->send(new UserLoginOtp($otpCode));
        return $this->sendResponse([
            'email' => $user->email,
            'purpose' => $purpose,
            'message' => 'OTP sent to your email'
        ], "Password Verified — Awaiting OTP");
    }
    public function admin_login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Incorrect email or password', [], 401);
        }
        if ($request->has('fcm_token') && !empty($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        $purpose = $user->hasAnyRole('admin');
        $otpCode = $this->otpService->generate($user, $purpose);
        Mail::to($user->email)->send(new UserLoginOtp($otpCode));
        return $this->sendResponse([
            'email' => $user->email,
            'purpose' => $purpose,
            'message' => 'OTP sent to your email'
        ], "Password Verified — Awaiting OTP");
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found', [], 404);
        }
        $purpose = $user->hasAnyRole(['admin', 'employee', 'citizen']);
        $isValid = $this->otpService->verify($user, $request->code, $purpose);
        if (!$isValid) {
            return $this->sendError('Invalid or expired OTP', [], 422);
        }
        $tokenName = $purpose == 'admin_login' ? 'admin-auth-token' : 'user-login';
        $token = $user->createToken($tokenName)->plainTextToken;
        return $this->sendResponse([
            'message' => 'OTP verified successfully',
            'token' => $token,
        ], 200);
    }

    public function user_logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $request->user()->currentAccessToken();
            return $this->sendResponse(null, "Logged out successfully");
        }
        return $this->sendError('User not authenticated', [], 401);
    }

    public function delete_account(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return $this->sendError('User not authenticated', [], 401);
        }
        if ($request->user()->currentAccessToken()) {
            $request->user()->tokens()->delete();
        }
        $user->delete();
        return $this->sendResponse(null, 'Account deleted successfully');
    }
}
