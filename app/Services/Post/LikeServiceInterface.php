<?php 

namespace App\Services\Post;

interface LikeServiceInterface {
    public function toggleLike($userId, $postId);
    public function likeCount($postId);
}