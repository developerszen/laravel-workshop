<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function defaultUser()
    {
        return User::find(1);
    }

    function headers() {
        $token = JWTAuth::fromUser($this->defaultUser());
        JWTAuth::setToken($token);

        $headers['Authorization'] = "Bearer $token";

        return $headers;
    }
}
