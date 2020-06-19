<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    function login (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json([ 'error' => 'unauthorized' ]);
        }

        return response()->json([ 'token' => $token ]);
    }
}
