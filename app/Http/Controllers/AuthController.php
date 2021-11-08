<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(AuthRequest $request)
    {
        //Attempt login
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        return response()->json([
            'data' =>  auth()->user(),
            'token' =>  auth()->user()->createToken('laravel_aouth_token')->plainTextToken
        ],200);
    }

}
