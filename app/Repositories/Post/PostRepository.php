<?php 

namespace App\Repositories\Post;

use App\Models\Post;

class PostRepository implements PostRepositoryInterface {
    public function all()
    {
        return Post::all();
    }

    public function find($id): ?Post
    {
        return Post::find($id);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update($id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    public function delete($id): bool
    {
        $post = Post::findOrFail($id);
        return $post->delete();
    }
}