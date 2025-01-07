<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();

            $request->image->move(public_path('images'), $imageName);

            $category = Category::create([
                'name' => $request->name,
                'image' => $imageName,
            ]);

            return $this->sendResponse($category, 'Category created successfully.', 201);
        }
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'image' => 'image',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        if (file_exists(public_path('images/'.$category->image))) {
            unlink(public_path('images/'.$category->image));
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();

            $request->image->move(public_path('images'), $imageName);

            $category->image = $imageName;
        }

        $category->update($request->all());

        return $this->sendResponse($category, 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.', 204);
    }
}
