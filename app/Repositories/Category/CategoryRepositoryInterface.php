<?php 

namespace App\Repositories\Category;

use App\Models\Category;

interface CategoryRepositoryInterface {
    public function all();
    public function find($id): ?Category;
    public function create(array $data): Category;
    public function update($id, array $data): Category;
    public function delete($id): bool;
}