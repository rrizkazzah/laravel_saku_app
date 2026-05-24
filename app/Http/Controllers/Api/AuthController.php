<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Google\Client;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // function buat register
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ],201);
    }

    // function buat logout
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout out successfully',
        ]);
    }

    // function buat login
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // cek user apakah ada di dalem database apa ngga
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ],404);
        }

        // cek password apakah benar apa ngga
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password',
            ],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ], 200);
    }


    // function buat login dengan google
   public function googleLogin(Request $request)
{
    $request->validate([
        'id_token' => 'required'
    ]);

    $client = new Client([
        'client_id' => env('GOOGLE_CLIENT_ID')
    ]);

    try {

        $payload = $client->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json([
                'message' => 'Invalid Google token'
            ], 401);
        }

    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Invalid Google token'
        ], 401);

    }

    $user = User::firstOrCreate(
        [
            'email' => $payload['email']
        ],
        [
            'name' => $payload['name'],
            'password' => bcrypt(Str::random(16))
        ]
    );

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login success',
        'token' => $token,
        'user' => $user
    ], 200);
}
}
