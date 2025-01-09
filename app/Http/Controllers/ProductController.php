<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends BaseController implements HasMiddleware
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:product_create', only: ['store']),
            new Middleware('permission:product_edit', only: ['update']),
            new Middleware('permission:product_delete', only: ['destroy']),
            new Middleware('permission:product_show', only: ['show']),
        ];
    }

    public function index()
    {
        $products = $this->productRepository->all();
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->all();
        $image = $request->file('image');
        $product = $this->productRepository->create($data, $image);
        return $this->sendResponse($product, 'Product created successfully.', 201);
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $data = $request->all();
        $image = $request->file('image');
        $product = $this->productRepository->update($id, $data, $image);
        return $this->sendResponse($product, 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $this->productRepository->delete($id);
        return $this->sendResponse(null, 'Product deleted successfully.', 204);
    }
}
