<?php

return [
    /**
     * Replace this login resource with your own if you have it
     */
    'resource' => \InnoFlash\LaraStart\Http\Resources\User::class,

    /**
     * Sets the default limit for paginated items
     */
    'limit' => 15,

    /**
     * Sets the default guard you want to use in the auth service
     */
    'guard' => 'web',

    /**
     * Sets the min length of a password for login and account creation
     */
    'password_min_length' => 6
];
