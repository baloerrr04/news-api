<?php 

namespace App\Services\Post;

interface CommentServiceInterface {
    public function getAll($postId);
    public function getById($id);
    public function store($postId, array $data);
    public function update($commentId, $postId, $userId, array $data);
    public function delete($id);
}