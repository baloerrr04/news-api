<?php

namespace App\Services\User;


interface UserServiceInterface {
    public function getAll();
    public function getById($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
}