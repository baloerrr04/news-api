<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }

    public function find($id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update($id, array $data): Category
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }

    public function delete($id): bool
    {
        return Category::destroy($id);
    }
}
