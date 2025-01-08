<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::with('category')->get();

        if (!$products) {
            return $this->sendError('Products not found.', 404);
        }

        $products = ProductResource::collection($products);

        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    public function store(CreateProductRequest $request)
    {
        $product = Product::create($request->all());
        return $this->sendResponse($product, 'Product created successfully.', 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $product = new ProductResource($product);

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $product->update($request->all());
        return $this->sendResponse($product, 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $product->delete();
        return $this->sendResponse(null, 'Product deleted successfully.', 204);
    }
}
