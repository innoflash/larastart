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
     * Sets the key to the resource above
     */
    'wrap' => 'user'
];
