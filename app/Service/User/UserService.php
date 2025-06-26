<?php

namespace App\Service\User;

use App\DTOs\User\UserDto;
use App\Repositories\User\UserRepository;
use App\Service\User\UserServiceInterface;

class UserService implements UserServiceInterface {

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->all();
    }

    public function getById($id)
    {
        return $this->userRepository->find($id);
    }

    public function store(UserDto $data)
    {
        return $this->userRepository->create($data->toArray());
    }

    public function update($id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
}