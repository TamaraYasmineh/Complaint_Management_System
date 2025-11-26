<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Http\Services\FirebaseService;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function test()
    {
        $this->firebase->getDatabase()->getReference("testing")
            ->set([
                'message' => 'Firebase Integration Successful!'
            ]);

        return 'Firebase Connected and Test Data Added!';
    }

    public function saveToken(Request $request)
{
    $request->validate([
        'fcm_token' => 'required|string'
    ]);

    $user = Auth::user();
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json(['message' => 'Token saved successfully']);
}

}
