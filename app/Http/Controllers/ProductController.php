<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::create($validated);
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Product not found.', 404);
        }

        $product->update($validated);
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
