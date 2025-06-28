<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepository;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll()
    {
        return $this->categoryRepository->all();
    }

    public function getById($id)
    {
        return $this->categoryRepository->find($id);
    }

    public function store(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->categoryRepository->create($data);
    }

    public function update($id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        return $this->categoryRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
