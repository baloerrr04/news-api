<?php 

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository implements AuthRepositoryInterface {

    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();    
    }

}