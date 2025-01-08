<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function all()
    {
        return $this->model->with('category')->get();
    }

    public function find($id)
    {
        return $this->model->with('category')->find($id);
    }

    public function create(array $data, $image = null)
    {
        if ($image) {
            $data['image'] = $this->uploadImage($image);
        }
        return $this->model->create($data);
    }

    public function update($id, array $data, $image = null)
    {
        $product = $this->model->find($id);
        if ($product) {
            if ($image) {
                // Delete old image if exists
                if ($product->image) {
                    $this->deleteImage($product->image);
                }
                $data['image'] = $this->uploadImage($image);
            }
            $product->update($data);
            return $product;
        }
        return null;
    }

    public function delete($id)
    {
        $product = $this->model->find($id);
        if ($product) {
            if ($product->image) {
                $this->deleteImage($product->image);
            }
            return $product->delete();
        }
        return false;
    }

    public function uploadImage($image)
    {
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('images'), $imageName);
        return $imageName;
    }

    public function deleteImage($imageName)
    {
        $imagePath = public_path('images/'.$imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return true;
        }
        return false;
    }
}
