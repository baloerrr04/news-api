<?php 

namespace App\Repositories\Post;

use App\Models\Comment;

interface CommentRepositoryInterface {
    public function all();
    public function find($id): ?Comment;
    public function findPostById($postId);
    public function create(array $data): Comment;
    public function update($id, array $data): Comment;
    public function delete($id): bool;
}