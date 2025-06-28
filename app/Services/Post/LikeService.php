<?php

namespace App\Services\Post;

use App\Repositories\Post\LikeRepository;

class LikeService implements LikeServiceInterface
{

    protected $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function toggleLike($userId, $postId)
    {
        if ($this->likeRepository->isLiked($userId, $postId)) {
            $this->likeRepository->unlikePost($userId, $postId);
            return ['liked' => false];
        } else {
            $this->likeRepository->likePost($userId, $postId);
            return ['liked' => true];
        }
    }

    public function likeCount($postId)
    {
        return $this->likeRepository->countLikes($postId);
    }
}
