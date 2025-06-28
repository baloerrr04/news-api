<?php

namespace App\Services\Post;

use App\Repositories\Post\CommentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CommentService implements CommentServiceInterface
{

    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAll($postId)
    {
        return $this->commentRepository->findPostById($postId);
    }

    public function getById($id)
    {
        return $this->commentRepository->find($id);
    }

    public function store($postId, array $data)
    {
        $data['user_id'] = auth()->id();
        $data['post_id'] = $postId;
        return $this->commentRepository->create($data);
    }

    public function update($commentId, $postId, $userId, array $data)
    {
        $comment = $this->commentRepository->findByPostAndId($postId, $commentId);
        if (!$comment) {
            throw new \Exception('Comment not found.');
        }
        $isAdmin = Auth::user()->hasRole('admin');
        $isOwner = $comment->user_id === $userId;

        if (!$isAdmin && !$isOwner) {
            throw new \Exception('Unauthorized to update comment.');
        }
       
        return $this->commentRepository->update($commentId, $data);
    }

    public function delete($id)
    {
        return $this->commentRepository->delete($id);
    }
}
