<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryController extends BaseController
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function store(CreateCategoryRequest $request)
    {
        if ($request->hasFile('image')) {
            $category = $this->categoryRepository->create(
                ['name' => $request->name],
                $request->file('image')
            );
            return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
        }
        return $this->sendError('Image is required.');
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }
        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        $category = $this->categoryRepository->update(
            $id,
            ['name' => $request->name],
            $request->hasFile('image') ? $request->file('image') : null
        );

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        if ($category->image) {
            $this->categoryRepository->deleteImage($category->image);
        }

        $this->categoryRepository->delete($id);
        return $this->sendResponse([], 'Category deleted successfully.');
    }

    /**
     * Display a listing of the products for a specific category.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexProducts($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return $this->sendError('Category not found.', 404);
        }

        return $this->sendResponse(ProductResource::collection($category->products), 'Products retrieved successfully.');
    }
}
