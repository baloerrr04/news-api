<?php

namespace App\Services\Post;

use Illuminate\Support\Str;


use App\Repositories\Post\PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function filter(array $filters)
    {
        return $this->postRepository->filter($filters);
    }

    public function store(array $data)
    {
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']);
        if (isset($data['thumbnail'])) {
            $file = $data['thumbnail'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('thumbnails', $filename, 'public');
            $data['thumbnail'] = 'storage/thumbnails/' . $filename;
        }
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

        if (isset($data['thumbnail'])) {
            $file = $data['thumbnail'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('thumbnails', $filename, 'public');
            $data['thumbnail'] = 'storage/thumbnails/' . $filename;

            if ($post->thumbnail && Storage::exists(str_replace('storage/', 'public/', $post->thumbnail))) {
                Storage::delete(str_replace('storage/', 'public/', $post->thumbnail));
            }
        } else {
            unset($data['thumbnail']); // Jangan ubah jika tidak dikirim
        }
        return $this->postRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }
}
