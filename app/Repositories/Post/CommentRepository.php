<?php

namespace App\Repositories\Post;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function all()
    {
        return Comment::all();
    }

    public function find($id): ?Comment
    {
        return Comment::find($id);
    }

    public function findPostById($postId)
    {
        return Comment::where('post_id', $postId)->with('user')->get();
    }

    public function findByPostAndId($postId, $commentId): ?Comment {
        return Comment::where('id', $commentId)->where('post_id', $postId)->first();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update($id, array $data): Comment
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }

    public function delete($id): bool
    {
        $comment = Comment::findOrFail($id);
        return $comment->delete();
    }
}
