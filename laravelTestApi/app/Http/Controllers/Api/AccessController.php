<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccessRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function login(AccessRequest $request)
    {
        if (!Auth::attempt($request->all())) {
            return response()->json(['message' => 'Account does not exist'], 401);
        }

        if (!$token = Auth::attempt($request->all())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["message" => "Sign out successful"], 200);
    }

    public function me()
    {
        return new UserResource(User::find(auth()->user()->id));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ]);
    }
}

