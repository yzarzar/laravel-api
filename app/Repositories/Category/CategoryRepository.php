<?php

namespace App\Repositories\Category;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
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
        $category = $this->model->find($id);
        if ($category) {
            if ($image) {
                // Delete old image if exists
                if ($category->image) {
                    $this->deleteImage($category->image);
                }
                $data['image'] = $this->uploadImage($image);
            }
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete($id)
    {
        $category = $this->model->find($id);
        if ($category) {
            return $category->delete();
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
