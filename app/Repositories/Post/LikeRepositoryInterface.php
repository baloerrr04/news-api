<?php

namespace App\Repositories\Post;

use App\Models\Like;

interface LikeRepositoryInterface
{
    public function likePost($userId, $postId);
    public function unlikePost(int $userId, int $postId);
    public function isLiked(int $userId, int $postId);
    public function countLikes(int $postId);
}
