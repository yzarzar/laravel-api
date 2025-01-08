<?php

namespace App\Repositories\Product;

use App\Models\Product;

/**
 * Interface ProductRepositoryInterface
 *
 * @package App\Repositories\Product
 */
interface ProductRepositoryInterface
{
    /**
     * Get all products
     *
     * @return \Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function all();

    /**
     * Find a product by id
     *
     * @param int $id
     *
     * @return Product|null
     */
    public function find($id);

    /**
     * Create a new product
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $image
     *
     * @return Product
     */
    public function create(array $data, $image = null);

    /**
     * Update a product
     *
     * @param int $id
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $image
     *
     * @return Product
     */
    public function update($id, array $data, $image = null);

    /**
     * Delete a product
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete($id);

    /**
     * Upload a product image
     *
     * @param \Illuminate\Http\UploadedFile $image
     *
     * @return string
     */
    public function uploadImage($image);

    /**
     * Delete a product image
     *
     * @param string $imageName
     *
     * @return bool
     */
    public function deleteImage($imageName);
}
