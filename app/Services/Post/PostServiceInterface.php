<?php 

namespace App\Services\Post;

interface PostServiceInterface {
    public function getAll();
    public function getById($id);
    public function filter(array $filters);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
}