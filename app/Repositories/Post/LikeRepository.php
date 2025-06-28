<?php 

namespace App\Repositories\Post;

use App\Models\Like;

class LikeRepository implements LikeRepositoryInterface {
    public function likePost($userId, $postId)
    {
        return Like::firstOrCreate([
            'user_id' => $userId,
            'post_id' => $postId
        ]);
    }

    public function unlikePost(int $userId, int $postId)
    {
        return Like::where('user_id', $userId)->where('post_id', $postId)->delete();
    }

    public function isLiked(int $userId, int $postId)
    {
        return Like::where('user_id', $userId)->where('post_id', $postId)->exists();
    }

    public function countLikes(int $postId)
    {
        return Like::where('post_id', $postId)->count();
    }
}