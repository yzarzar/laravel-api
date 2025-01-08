<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends BaseController
{
    public function store(CreateCategoryRequest $request)
    {
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $category = Category::create([
                'name' => $request->name,
                'image' => $imageName,
            ]);

            return $this->sendResponse($category, 'Category created successfully.', 201);
        }
        return $this->sendError('Image file is required.', [], 422);
    }

    public function index()
    {
        $categories = Category::all();

        $categories = CategoryResource::collection($categories);

        return $this->sendResponse($categories, 'Categories retrieved successfully.');
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        $category = new CategoryResource($category);

        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && file_exists(public_path('images/'.$category->image))) {
                unlink(public_path('images/'.$category->image));
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->fill($request->only(['name']));
        $category->save();

        return $this->sendResponse($category, 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        if ($category->image && file_exists(public_path('images/'.$category->image))) {
            unlink(public_path('images/'.$category->image));
        }

        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.', 204);
    }
}
