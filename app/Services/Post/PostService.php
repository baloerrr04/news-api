<?php

namespace App\Services\Post;

use Illuminate\Support\Str;


use App\Repositories\Post\PostRepository;
use Illuminate\Support\Facades\Auth;

class PostService implements PostServiceInterface
{

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll()
    {
        return $this->postRepository->all();
    }

    public function getById($id)
    {
        return $this->postRepository->find($id);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']);
        return $this->postRepository->create($data);
    }

    public function update($id, array $data)
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new \Exception("Post not found.");
        }
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return $this->postRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }
}
