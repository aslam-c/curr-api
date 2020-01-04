<?php

namespace App\Events;


class jwtstring
{
    public $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}
