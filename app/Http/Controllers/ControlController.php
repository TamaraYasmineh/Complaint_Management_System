<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlController extends BaseController
{
    public function upgradeUser($id)
    {
        $user = User::findOrFail($id);

        $user->syncRoles(['employee']);

        return response()->json([
            'message' => 'user upgraded successfully',
            'user' => $user
        ]);
    }

    public function downgradeUser($id)
    {
        $authUser = Auth::user();

        $user = User::findOrFail($id);

        $user->syncRoles(['citizen']);

        return response()->json([
            'message' => 'User downgraded successfully to regular citizen',
            'user' => $user->only(['id', 'name', 'email']),
            'new_role' => $user->getRoleNames()->first(),
        ], 200);
    }
    public function showUsers()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department' => $user->department,
                'role' => $user->getRoleNames()->first() ?? 'no role',
            ];
        });

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found'
            ], 200);
        }
    return $this->sendResponse($users,'success');

        // return response()->json([
        //     'users' => $users
        // ], 200);
    }
}
