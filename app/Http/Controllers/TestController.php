<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
//   public function register(Request $request)
//     {
//         $data = $request->validate([
//             'username' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8',
//             'department'=>'nullable|string|max:255'
//         ]);

//         $user = User::create([
//             'username' => $data['username'],
//             'email' => $data['email'],
//             'password' => Hash::make($data['password']),
//             'department'=>$data[ 'department']
//         ]);

//         // $user->assignRole('user');

//         $token = $user->createToken('API Token')->plainTextToken;

//         return response()->json([
//             'message' => ' user registered successfully ',
//             'user' => $user->only(['id', 'username', 'email']),
//             'token' => $token,
//             // 'role' => $user->getRoleNames()->first(),
//         ], 201);
//     }
}
