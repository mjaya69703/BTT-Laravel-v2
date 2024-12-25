<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Use Models
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'role'=>'required|in:admin,user',
            'email'=>'required|string|email|unique:users,email',
            'password'=>'required|min:8',
            'confirm_password'=>'required|min:8|same:password',
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'role'=>$data['role'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password'])
        ]);
        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response,201);
    }

    public function login(Request $request)
    {   
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response,201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'See you again!',
        ];

        return response($response);
    }
}
