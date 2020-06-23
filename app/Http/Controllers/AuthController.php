<?php

namespace App\Http\Controllers;

use App\Mail\PasswordRecovery;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;

    protected $decayMinutes = 2;

    function login (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $token = auth()->attempt($credentials);

        if (!$token) {
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }

        $this->clearLoginAttempts($request);

        return response()->json([
            'token' => $token
        ]);
    }

    function requestPasswordRecovery(Request $request) {
        $request->validate([
            'email' => 'required|exists:users,email',
        ]);

        $email = $request->input('email');

        $reset_token = $this->generateToken();

        $user = User::where('email', $email)->select('id', 'name', 'reset_token')->firstOrFail();

        $user->update([
            'reset_token' => $reset_token,
        ]);

        Mail::to($email)->send(new PasswordRecovery($user));

        return response([], 204);
    }

    function passwordRecovery(Request $request) {
        $request->validate([
           'new_password' => 'required|string'
        ]);

        $user = User::where([
            'reset_token' => $request->query('reset_token')
        ])
            ->firstOrFail();

        $user->update([
            'password' => bcrypt($request->input('new_password')),
            'reset_token' => null,
        ]);

        return response([], 204);
    }

    private function generateToken() {
        $token = Str::random(80);

        $token_exists = User::where('reset_token', $token)->exists();

        if($token_exists) {
            $this->generateToken();
        }

        return $token;
    }
}
