<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create($data);
        $token = $user->createToken($request->name);
        return [
            'user' => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => "credentials not correct",
            ];
        }
        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return [
            'message' => "you logged out",
        ];
    }
}
