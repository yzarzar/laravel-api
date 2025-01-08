<?php

namespace App\Repositories\Category;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data, $image = null);
    public function update($id, array $data, $image = null);
    public function delete($id);
    public function uploadImage($image);
    public function deleteImage($imageName);
}
