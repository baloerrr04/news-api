<?php 

namespace App\Repositories\Auth;

use App\Models\User;

interface AuthRepositoryInterface {
    public function findByName(string $name): ?User;
}