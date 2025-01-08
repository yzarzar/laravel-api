<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data, $image = null);
    public function update($id, array $data, $image = null);
    public function delete($id);
    public function uploadImage($image);
    public function deleteImage($imageName);
}
