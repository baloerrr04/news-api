<?php

namespace App\Service\User;

use App\DTOs\User\UserDto;

interface UserServiceInterface {
    public function getAll();
    public function getById($id);
    public function store(UserDto $data);
    public function update($id, array $data);
    public function delete($id);
}