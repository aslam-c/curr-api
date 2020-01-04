<?php

namespace App\Listeners;

use App\Events\jwtstring;
use Firebase\JWT\JWT;

class generatejwt
{
    public function handle($event)
    {
        $payload = $event->payload;
        $context = [
            'iat' => now()->timestamp,
            'exp' => now()->addDays(7)->timestamp,
            $payload['key'] => $payload['value']
        ];


        $secret = config('services.jwt.secret');

        $encoded = JWT::encode($context, $secret);
        return $encoded;
    }
}
