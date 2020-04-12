<?php

namespace InnoFlash\LaraStart\Services;

class AuthService
{
    public function attemptLogin(array $credentials = [], string $guard = 'api')
    {
        if (! count($credentials)) {
            $credentials = request(['email', 'password']);
        }

        if (! $guard) {
            $guard = config('larastart.guard');
        }

        if (! $token = auth($guard)->attempt($credentials)) {
            return $this->validationFailed();
        }

        $class = config('larastart.resource');

        return [
            config('larastart.wrap') => new $class(auth($guard)->user()),
            'token' => [
                'access_token' => $token,
                'expires_in' => auth($guard)->factory()->getTTL() * 60,
                'type' => 'bearer',
            ],
        ];
    }

    public function authenticatedUser(string $guard = 'api')
    {
        $class = config('larastart.resource');

        if (! $guard) {
            $guard = config('larastart.guard');
        }

        return new $class(auth($guard)->user());
    }

    public function validationFailed()
    {
        abort(401, 'Invalid login credentials');
    }
}
