<?php

namespace App\Services\Feeder;

class AuthService
{
    protected FeederClient $client;

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    public function login(string $username, string $password)
    {
        return $this->client->authenticate($username, $password);
    }
}
