<?php 

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository implements AuthRepositoryInterface {

    public function findByName(string $name): ?User
    {
        return User::where('name', $name)->first();    
    }

}