<?php

namespace App\Service\Auth;

interface AuthServiceInterface {

    public function register(array $data);
    public function login (string $username, string $password);
    public function refreshToken(string $refreshToken);
}