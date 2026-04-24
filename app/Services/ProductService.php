<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products with filters
     */
    public function getAllProducts(array $filters = [], int $perPage = 15)
    {
        try {
            return $this->productRepository->getAll($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Failed to fetch products: ' . $e->getMessage());
            throw new \RuntimeException('Unable to fetch products');
        }
    }

    /**
     * Get single product by ID
     */
    public function getProductById(int $id)
    {
        try {
            return $this->productRepository->findById($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Product with ID {$id} not found");
        } catch (\Exception $e) {
            Log::error('Failed to fetch product: ' . $e->getMessage());
            throw new \RuntimeException('Unable to fetch product');
        }
    }

    /**
     * Create new product
     */
    public function createProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->create($data);
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create product: ' . $e->getMessage());
            throw new \RuntimeException('Unable to create product');
        }
    }

    /**
     * Update existing product
     */
    public function updateProduct(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->update($id, $data);
            DB::commit();
            return $product;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update product: ' . $e->getMessage());
            throw new \RuntimeException('Unable to update product');
        }
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->productRepository->delete($id);
            DB::commit();
            return $result;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete product: ' . $e->getMessage());
            throw new \RuntimeException('Unable to delete product');
        }
    }

    /**
     * Restore deleted product
     */
    public function restoreProduct(int $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->productRepository->restore($id);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to restore product: ' . $e->getMessage());
            throw new \RuntimeException('Unable to restore product');
        }
    }
}
